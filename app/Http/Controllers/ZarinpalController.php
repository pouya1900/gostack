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
use App\Helper;
use Mail;
use Carbon\Carbon;
use App\Models\PaymentGateways;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class ZarinpalController extends Controller
{
    use Traits\FunctionsTrait;

    private $merchantId;
    private $portalDescription;
    private $base;
    private $payment_page;

    public function __construct(AdminSettings $settings, Request $request)
    {
        $this->settings = $settings::first();
        $this->request = $request;
        $this->base = "https://api.zarinpal.com/pg";
        $this->merchantId = env('ZARINPAL_MERCHANTID');
        $this->payment_page = "https://www.zarinpal.com/pg/StartPay/";
    }

    // Buy photo
    public function buy()
    {

        if (!$this->request->expectsJson()) {
            abort(404);
        }
        try {

            // Get Payment Gateway
            $payment = PaymentGateways::whereId($this->request->payment_gateway)->whereName('Shetab')->firstOrFail();

            // Get Image
            $image = Images::where('token_id', $this->request->token)->firstOrFail();

            $priceItem = $this->settings->default_price_photos ?: $image->price;

            $itemPrice = $this->priceItem($this->request->license, $priceItem, $this->request->type);

            // Admin and user earnings calculation
            $earnings = $this->earningsAdminUser($image->user()->author_exclusive, $itemPrice, $payment->fee, $payment->fee_cents);

            $config = config('shetab');

            // Insert Purchase status 'Pending'
            $purchase = $this->purchase(
                'pp_' . str_random(25),
                $image,
                auth()->id(),
                Helper::amountGross($itemPrice),
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

            $urlSuccess = route('shetab.buy.success', ['id' => $purchase->id]);

            $order = $this->payRequest(Helper::amountGross($itemPrice), $urlSuccess);

            // Update Order Id
            Purchases::whereId($purchase->id)->update(['txn_id' => $order['id']]);

            return response()->json([
                'success' => true,
                'url'     => $order['href'],
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

        // Check if deposit exists
        $purchase = Purchases::whereId($request->id)->whereUserId(auth()->id())->first();

        try {
            $status = $request->Status;

            if ($status != "OK") {
                // Delete Invoice
                Invoices::wherePurchasesId($purchase->id)->delete();

                // Delete Purchase
                $purchase->delete();

                return redirect('user/dashboard/purchases')->withError(__('misc.payment_not_confirmed'));
            }

            // Get PaymentOrder using our transaction ID

            $order = $this->payVerify($purchase->txn_id, $purchase->price);


            if ($order['status'] == 1) {

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

    public function payRequest($amount, $callbackUrl)
    {
        $data = [
            'merchant_id'  => $this->merchantId,
            'amount'       => $amount,
            'callback_url' => $callbackUrl,
            'description'  => "خرید از گو استک",
        ];
        $jsonData = json_encode($data);
        $ch = curl_init($this->base . '/v4/payment/request.json');
        curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData),
        ]);
        $result = curl_exec($ch);
        $err = curl_error($ch);
        $result = json_decode($result, true);
        curl_close($ch);

        if (!empty($result["data"]) && $result["data"]["code"] == 100) {

            return [
                'id'   => $result["data"]["authority"],
                'href' => $this->payment_page . $result["data"]["authority"],
            ];

        }

        return 0;

    }

    public function payVerify($authority, $amount)
    {


        $data = [
            'merchant_id' => $this->merchantId,
            'authority'   => $authority,
            'amount'      => $amount,
        ];
        $jsonData = json_encode($data);
        $ch = curl_init($this->base . '/v4/payment/verify.json');
        curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData),
        ]);
        $result = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        $result = json_decode($result, true);

        if (!empty($result["data"]) && $result["data"]["code"] == 100) {
            return [
                'status' => 1,
            ];
        }
        return 0;
    }

}
