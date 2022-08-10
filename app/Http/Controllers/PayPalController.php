<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\AdminSettings;
use App\Models\Images;
use App\Models\Deposits;
use App\Models\Invoices;
use App\Models\Purchases;
use App\Models\User;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use App\Helper;
use Mail;
use Carbon\Carbon;
use App\Models\PaymentGateways;

class PayPalController extends Controller
{
    use Traits\FunctionsTrait;

    public function __construct(AdminSettings $settings, Request $request)
    {
        $this->settings = $settings::first();
        $this->request = $request;
    }

    public function show()
    {

        if (!$this->request->expectsJson()) {
            abort(404);
        }

        // Get Payment Gateway
        $payment = PaymentGateways::findOrFail($this->request->payment_gateway);

        $feePayPal = $payment->fee;
        $centsPayPal = $payment->fee_cents;

        $taxes = $this->settings->tax_on_wallet ? ($this->request->amount * auth()->user()->isTaxable()->sum('percentage') / 100) : 0;

        $amountFixed = number_format($this->request->amount + ($this->request->amount * $feePayPal / 100) + $centsPayPal + $taxes, 2, '.', '');

        try {

            // Insert Deposit status 'Pending'
            $deposit = $this->deposit(
                auth()->id(),
                'pp_' . str_random(25),
                $this->request->amount,
                'PayPal',
                $this->settings->tax_on_wallet ? auth()->user()->taxesPayable() : null,
                'pending'
            );

            $urlSuccess = route('paypal.success', ['id' => $deposit->id]);
            $urlCancel = route('paypal.cancel', [
                'type' => 'deposit',
                'id'   => $deposit->id,
            ]);

            $provider = new PayPalClient();

            $token = $provider->getAccessToken();
            $provider->setAccessToken($token);
            $order = $provider->createOrder([
                "intent"              => "CAPTURE",
                'application_context' =>
                    [
                        'return_url'          => $urlSuccess,
                        'cancel_url'          => $urlCancel,
                        'shipping_preference' => 'NO_SHIPPING',
                    ],

                "purchase_units" => [
                    [
                        "amount"      => [
                            "currency_code" => $this->settings->currency_code,
                            "value"         => $amountFixed,
                            'breakdown'     => [
                                'item_total' => [
                                    "currency_code" => $this->settings->currency_code,
                                    "value"         => $amountFixed,
                                ],
                            ],
                        ],
                        'description' => trans('misc.add_funds_desc'),

                        'items' => [
                            [
                                'name'        => trans('misc.add_funds_desc'),
                                'category'    => 'DIGITAL_GOODS',
                                'quantity'    => '1',
                                'unit_amount' => [
                                    "currency_code" => $this->settings->currency_code,
                                    "value"         => $amountFixed,
                                ],
                            ],
                        ],
                    ],
                ],
            ]);

            // Update Order Id
            Deposits::whereId($deposit->id)->update(['txn_id' => $order['id']]);

            return response()->json([
                'success' => true,
                'url'     => $order['links'][1]['href'],
            ]);

        } catch (\Exception $e) {

            \Log::debug($order);

            // Delete Invoice
            Invoices::whereDepositsId($deposit->id)->delete();

            // Delete deposit
            Deposits::whereId($deposit->id)->delete();

            return response()->json([
                'errors' => ['error' => $e->getMessage()],
            ]);
        }
    }

    public function success(Request $request)
    {
        // Init PayPal
        $provider = new PayPalClient();
        $token = $provider->getAccessToken();
        $provider->setAccessToken($token);

        // Check if deposit exists
        $deposit = Deposits::whereId($request->id)->whereUserId(auth()->id())->first();

        try {
            // Get PaymentOrder using our transaction ID
            $order = $provider->capturePaymentOrder($deposit->txn_id);
            $amount = $order['purchase_units'][0]['payments']['captures'][0]['amount']['value'];

            if ($order['status'] === "COMPLETED" && $amount > $deposit->amount) {
                // Save that deposit
                $deposit->status = 'active';
                $deposit->save();

                // Update Invoice to 'Paid'
                Invoices::whereDepositsId($deposit->id)->update(['status' => 'paid']);

                // Add Funds to User
                $deposit->user()->increment('funds', $deposit->amount);

                // Return Redirect
                return redirect('user/dashboard/add/funds');
            } else {
                // Delete Invoice
                Invoices::whereDepositsId($deposit->id)->delete();

                // Delete Deposit
                $deposit->delete();

                return redirect('user/dashboard/add/funds')->withError(__('misc.payment_not_confirmed'));
            }

        } catch (\Exception $e) {

            // Delete Invoice
            Invoices::whereDepositsId($deposit->id)->delete();

            // Delete Deposit
            $deposit->delete();

            return redirect('user/dashboard/add/funds')->withError($e->getMessage());
        }

    }// End method success

    // Buy photo
    public function buy()
    {

        if (!$this->request->expectsJson()) {
            abort(404);
        }
        try {

            // Get Payment Gateway
            $payment = PaymentGateways::whereId($this->request->payment_gateway)->whereName('PayPal')->firstOrFail();

            // Get Image
            $image = Images::where('token_id', $this->request->token)->firstOrFail();

            $priceItem = $this->settings->default_price_photos ?: $image->price;

            $itemPrice = $this->priceItem($this->request->license, $priceItem, $this->request->type);

            // Admin and user earnings calculation
            $earnings = $this->earningsAdminUser($image->user()->author_exclusive, $itemPrice, $payment->fee, $payment->fee_cents);

            // Insert Purchase status 'Pending'
            $purchase = $this->purchase(
                'pp_' . str_random(25),
                $image,
                auth()->id(),
                $itemPrice,
                $earnings['user'],
                $earnings['admin'],
                $this->request->type,
                $this->request->license,
                $earnings['percentageApplied'],
                'PayPal',
                auth()->user()->taxesPayable(),
                false,
                '0'
            );

            $itemName = trans('misc.' . $this->request->type . '_photo') . ' - ' . trans('misc.license_' . $this->request->license);

            $urlSuccess = route('buy.success', ['id' => $purchase->id]);

            $urlCancel = route('paypal.cancel', [
                'type' => 'purchase',
                'id'   => $purchase->id,
            ]);

            $provider = new PayPalClient();

            $token = $provider->getAccessToken();
            $provider->setAccessToken($token);
            $order = $provider->createOrder([
                "intent"              => "CAPTURE",
                'application_context' =>
                    [
                        'return_url'          => $urlSuccess,
                        'cancel_url'          => $urlCancel,
                        'shipping_preference' => 'NO_SHIPPING',
                    ],

                "purchase_units" => [
                    [
                        "amount"      => [
                            "currency_code" => $this->settings->currency_code,
                            "value"         => Helper::amountGross($itemPrice),
                            'breakdown'     => [
                                'item_total' => [
                                    "currency_code" => $this->settings->currency_code,
                                    "value"         => Helper::amountGross($itemPrice),
                                ],
                            ],
                        ],
                        'description' => $itemName,

                        'items' => [
                            [
                                'name'        => $itemName,
                                'category'    => 'DIGITAL_GOODS',
                                'quantity'    => '1',
                                'unit_amount' => [
                                    "currency_code" => $this->settings->currency_code,
                                    "value"         => Helper::amountGross($itemPrice),
                                ],
                            ],
                        ],
                    ],
                ],
            ]);

            // Update Order Id
            Purchases::whereId($purchase->id)->update(['txn_id' => $order['id']]);

            return response()->json([
                'success' => true,
                'url'     => $order['links'][1]['href'],
            ]);

        } catch (\Exception $e) {

            // Delete Invoice
            Invoices::wherePurchasesId($purchase->id)->delete();

            // Delete purchase
            Purchases::whereId($purchase->id)->delete();

            return response()->json([
                'errors' => ['error' => $e->getMessage()],
            ]);
        }
    }// End method buy

    public function successBuy(Request $request)
    {
        // Init PayPal
        $provider = new PayPalClient();
        $token = $provider->getAccessToken();
        $provider->setAccessToken($token);

        // Check if deposit exists
        $purchase = Purchases::whereId($request->id)->whereUserId(auth()->id())->first();

        try {
            // Get PaymentOrder using our transaction ID
            $order = $provider->capturePaymentOrder($purchase->txn_id);
            $amount = $order['purchase_units'][0]['payments']['captures'][0]['amount']['value'];

            if ($order['status'] === "COMPLETED" && $amount >= $purchase->price) {

                // Update Invoice to 'Paid'
                Invoices::wherePurchasesId($purchase->id)->update(['status' => 'paid']);

                // Add Balance And Notify to User
                $this->AddBalanceAndNotify($purchase->images(), $purchase->user_id, $purchase->earning_net_seller);

                // Insert Download
                $this->downloads($purchase->images_id, $purchase->user_id);

                // Referred
                $earningAdminReferred = $this->referred($purchase->user_id, $purchase->earning_net_admin, 'photo');

                // Save that purchase
                $purchase->approved = '1';
                $purchase->earning_net_admin = $earningAdminReferred ?: $purchase->earning_net_admin;
                $purchase->referred_commission = $earningAdminReferred ? true : false;
                $purchase->save();

                // Return Redirect
                return redirect('user/dashboard/purchases');
            } else {
                // Delete Invoice
                Invoices::wherePurchasesId($purchase->id)->delete();

                // Delete Purchase
                $purchase->delete();

                return redirect('user/dashboard/purchases')->withError(__('misc.payment_not_confirmed'));
            }

        } catch (\Exception $e) {

            // Delete Invoice
            Invoices::wherePurchasesId($purchase->id)->delete();

            // Delete Purchase
            $purchase->delete();

            return redirect('user/dashboard/purchases')->withError($e->getMessage());
        }

    }// End method successBuy

    public function cancel(Request $request)
    {
        switch ($request->type) {
            case 'purchase':

                $purchase = Purchases::whereId($request->id)->whereUserId(auth()->id())->first();

                // Delete Invoice
                Invoices::wherePurchasesId($purchase->id)->delete();

                // Delete Purchase
                $purchase->delete();

                return redirect('user/dashboard/purchases')->withError(__('misc.purchase_canceled'));

                break;

            case 'deposit':
                $deposit = Deposits::whereId($request->id)->whereUserId(auth()->id())->first();

                // Delete Invoice
                Invoices::whereDepositsId($deposit->id)->delete();

                // Delete Deposit
                $deposit->delete();

                return redirect('user/dashboard/add/funds');
                break;
        }

        return redirect('/');

    }// End method cancel
}
