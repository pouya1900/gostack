<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\AdminSettings;
use App\Models\Images;
use App\Models\Deposits;
use App\Models\User;
use App\Models\Plans;
use App\Helper;
use Mail;
use Carbon\Carbon;
use App\Models\PaymentGateways;

class StripeController extends Controller
{
  use Traits\FunctionsTrait;

  public function __construct( AdminSettings $settings, Request $request) {
    $this->settings = $settings::first();
    $this->request = $request;
  }

  // Add Funds to wallet
  public function show()
  {
    if (! $this->request->expectsJson()) {
        abort(404);
    }

    // Get Payment Gateway
    $payment = PaymentGateways::whereId($this->request->payment_gateway)->whereName('Stripe')->firstOrFail();

    //<---- Validation
		$validator = Validator::make($this->request->all(), [
      'amount' => 'required|integer|min:'.$this->settings->min_deposits_amount.'|max:'.$this->settings->max_deposits_amount,
      'payment_gateway' => 'required'
    ]);

			if ($validator->fails()) {
			        return response()->json([
					        'success' => false,
					        'errors' => $validator->getMessageBag()->toArray(),
					    ]);
			    }

    $email = auth()->user()->email;

  	$feeStripe   = $payment->fee;
  	$centsStripe =  $payment->fee_cents;

    $taxes = $this->settings->tax_on_wallet ? ($this->request->amount * auth()->user()->isTaxable()->sum('percentage') / 100) : 0;

    if ($this->settings->currency_code == 'JPY') {
      $amountFixed = round($this->request->amount + ($this->request->amount * $feeStripe / 100) + $centsStripe + $taxes);
    } else {
      $amountFixed = number_format($this->request->amount + ($this->request->amount * $feeStripe / 100) + $centsStripe + $taxes, 2, '.', '');
    }

  	$amountGross = ($this->request->amount);
  	$amount   = $this->settings->currency_code == 'JPY' ? $amountFixed : ($amountFixed*100);
  	$currency_code = $this->settings->currency_code;
  	$description = trans('misc.add_funds_desc');
  	$nameSite = $this->settings->title;

    $stripe = new \Stripe\StripeClient($payment->key_secret);

    $checkout = $stripe->checkout->sessions->create([
      'line_items' => [[
        'price_data' => [
          'currency' => $currency_code,
          'product_data' => [
            'name' => $description,
          ],
          'unit_amount' => $amount,
        ],
        'quantity' => 1,
      ]],
      'mode' => 'payment',

          'metadata' => [
            'userId' => auth()->id(),
            'amount' => $this->request->amount,
            'taxes' => $this->settings->tax_on_wallet ? auth()->user()->taxesPayable() : null,
            'mode' => 'deposit'
          ],

      'payment_method_types' => ['card'],
      'customer_email' => auth()->user()->email,

      'success_url' => url('user/dashboard/add/funds'),
      'cancel_url' => url('user/dashboard/add/funds'),
    ]);

    return response()->json([
      'success' => true,
      'url' => $checkout->url,
    ]);
  }// End Add funds

  // Process Purchase
  public function buy()
  {
    if (! $this->request->expectsJson()) {
        abort(404);
    }

    // Get Payment Gateway
    $payment = PaymentGateways::whereId($this->request->payment_gateway)->whereName('Stripe')->firstOrFail();

    // Get Image
    $image = Images::where('token_id', $this->request->token)->firstOrFail();

    //<---- Validation
		$validator = Validator::make($this->request->all(), [
      'payment_gateway' => 'required'
    ]);

		if ($validator->fails()) {
		        return response()->json([
				        'success' => false,
				        'errors' => $validator->getMessageBag()->toArray(),
				    ]);
		    }

    $priceItem = $this->settings->default_price_photos ?: $image->price;

    $itemPrice = $this->priceItem($this->request->license, $priceItem, $this->request->type);

  	$amount = $this->settings->currency_code == 'JPY' ? Helper::amountGross($itemPrice) : (Helper::amountGross($itemPrice)*100);
  	$currency_code = $this->settings->currency_code;
  	$description = trans('misc.stock_photo_purchase');

    $stripe = new \Stripe\StripeClient($payment->key_secret);

    $checkout = $stripe->checkout->sessions->create([
      'line_items' => [[
        'price_data' => [
          'currency' => $currency_code,
          'product_data' => [
            'name' => $description,
          ],
          'unit_amount' => $amount,
        ],
        'quantity' => 1,
      ]],
      'mode' => 'payment',

          'metadata' => [
            'userId' => auth()->id(),
            'token' => $this->request->token,
            'license' => $this->request->license,
            'type' => $this->request->type,
            'taxes' => auth()->user()->taxesPayable(),
            'mode' => 'sale'
          ],

      'payment_method_types' => ['card'],
      'customer_email' => auth()->user()->email,

      'success_url' => url('user/dashboard/purchases'),
      'cancel_url' => $this->request->urlCancel ?? url('/'),
    ]);

    return response()->json([
      'success' => true,
      'url' => $checkout->url,
    ]);

  }// End method buy

  public function subscription()
  {
    if (! $this->request->expectsJson()) {
        abort(404);
    }

    $plan = Plans::wherePlanId($this->request->plan)->whereStatus('1')->firstOrFail();

    // Check Subscription
    if (auth()->user()->getSubscription()) {
      return response()->json([
          'success' => false,
          'errors' => ['error' => trans('misc.subscription_exists')],
      ]);
    }

    $payment = PaymentGateways::whereName('Stripe')->whereEnabled(1)->first();
    $stripe = new \Stripe\StripeClient($payment->key_secret);
    $planId = $plan->plan_id;
    $planPrice = $this->request->interval == 'month' ? $plan->price : $plan->price_year;

    // Verify Plan Exists
    try {
      $planCurrent = $stripe->plans->retrieve($planId, []);
      $pricePlanOnStripe = ($planCurrent->amount / 100);

      // We check if the plan changed price
      if ($pricePlanOnStripe != $planPrice) {
        // Delete old plan
        $stripe->plans->delete($planId, []);

        // Delete Product
        $stripe->products->delete($planCurrent->product, []);

        // We create the plan with new price
        $this->createPlan($payment->key_secret, $plan, $this->request->interval);
      }

    } catch (\Exception $exception) {

      // Create New Plan
      $this->createPlan($payment->key_secret, $plan, $this->request->interval);
    }

      try {

        // Create New subscription
        $metadata = [
          'interval' => $this->request->interval,
          'taxes' => auth()->user()->taxesPayable()
        ];

        $checkout = auth()->user()->newSubscription('main', $planId)
        ->withMetadata($metadata)
          ->checkout([
            'success_url' => route('success.subscription', ['alert' => 'payment']),
            'cancel_url' => url('pricing'),
        ]);

        return response()->json([
          'success' => true,
          'url' => $checkout->url,
        ]);

      } catch (\Exception $exception) {

        \Log::debug($exception);

        return response()->json([
          'success' => false,
          'errors' => ['error' => $exception->getMessage()]
        ]);
    }
  }

  private function createPlan($keySecret, $plan, $interval)
  {
    $stripe = new \Stripe\StripeClient($keySecret);

    switch ($interval) {
      case 'month':
        $interval = 'month';
        $interval_count = 1;
        $price = $plan->price;
        break;

      case 'year':
        $interval = 'year';
        $interval_count = 1;
        $price = $plan->price_year;
        break;
    }

    // If it does not exist we create the plan
    $stripe->plans->create([
        'currency' => $this->settings->currency_code,
        'interval' => $interval,
        'interval_count' => $interval_count,
        "product" => [
            "name" => trans('misc.subscription_plan', ['name' => $plan->name]),
        ],
        'nickname' => $plan->name,
        'id' => $plan->plan_id,
        'amount' => $this->settings->currency_code == 'JPY' ? $price : ($price * 100),
    ]);
  }

}
