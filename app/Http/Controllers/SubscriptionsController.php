<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\AdminSettings;
use App\Models\Images;
use App\Models\Subscriptions;
use App\Models\User;
use App\Models\Plans;
use App\Helper;
use Mail;
use Carbon\Carbon;
use App\Models\PaymentGateways;

class SubscriptionsController extends Controller
{
	use Traits\FunctionsTrait;

	public function __construct(AdminSettings $settings, Request $request) {
		$this->settings = $settings::first();
		$this->request = $request;
	}

    public function buy()
		{
			// Check Plan exists
			$plan = Plans::wherePlanId($this->request->plan)->whereStatus('1')->firstOrFail();

			$messages = [
			'interval.in' => trans('misc.error'),
		];

		//<---- Validation
		$validator = Validator::make($this->request->all(), [
				'payment_gateway' => 'required',
				'interval' => 'required|in:month,year',
				'plan' => 'required',
	    	], $messages);

			if ($validator->fails()) {
			        return response()->json([
					        'success' => false,
					        'errors' => $validator->getMessageBag()->toArray(),
					    ]);
			    }

					// Wallet
	        if ($this->request->payment_gateway == 'wallet') {
	          return $this->wallet();
	        }

					// Get name of Payment Gateway
					$payment = PaymentGateways::whereId($this->request->payment_gateway)->whereSubscription('1')->first();

					if (! $payment) {
						return response()->json([
								'success' => false,
								'errors' => ['error' => trans('misc.payments_error')],
						]);
					}

					$routePayment = str_slug($payment->name).'.subscription';

					// Send data to the payment processor
					return redirect()->route($routePayment, $this->request->except(['_token']));

		}//<--------- End Method  Send

		private function wallet()
		{
			$plan = Plans::wherePlanId($this->request->plan)->whereStatus('1')->firstOrFail();
			$planPrice = $this->request->interval == 'month' ? $plan->price : $plan->price_year;

			if (auth()->user()->funds < Helper::amountGross($planPrice)) {
        return response()->json([
          "success" => false,
          "errors" => ['error' => __('misc.not_enough_funds')]
        ]);
      }

	    // Check Subscription
	    if (auth()->user()->getSubscription()) {
	      return response()->json([
	          'success' => false,
	          'errors' => ['error' => trans('misc.subscription_exists')],
	      ]);
	    }

			// Insert DB
      $subscription              = new Subscriptions();
      $subscription->user_id     = auth()->id();
      $subscription->stripe_price = $plan->plan_id;
      $subscription->ends_at     = Helper::planInterval($this->request->interval);
      $subscription->rebill_wallet = 'on';
      $subscription->interval = $this->request->interval;
      $subscription->taxes = auth()->user()->taxesPayable();
			$subscription->payment_gateway = 'Wallet';
      $subscription->save();

			// Add downloads to user
			auth()->user()->update(['downloads' => $plan->downloads_per_month]);

			// Create Invoice
			$this->invoiceSubscription($subscription->user_id, $subscription->id, $planPrice, auth()->user()->taxesPayable(), true);

			// Subtract user funds
      auth()->user()->decrement('funds', Helper::amountGross($planPrice));

      return response()->json([
        "success" => true,
        'url' => route('success.subscription')
      ]);

		}// End Method wallet

		public function success(Request $request)
		{
			$message = __('misc.subscription_success');
			sleep(2);

			if ($request->alert) {
				return redirect('account/subscription')->withSuccessWithAlert($message);
			}

			return redirect('account/subscription')->withSuccess($message);
		}

		public function cancel(Request $request)
		{
			$checkSubscription = auth()->user()->mySubscription()->whereId($request->id)->firstOrFail();
			$payment = PaymentGateways::whereName('Stripe')->whereEnabled(1)->firstOrFail();

			if ($checkSubscription->stripe_id) {

				try {
					$stripe = new \Stripe\StripeClient($payment->key_secret);
          $response = $stripe->subscriptions->cancel($checkSubscription->stripe_id);
        } catch (\Exception $e) {
          return back()->withError($e->getMessage());
        }

        sleep(2);

        $checkSubscription->ends_at = date('Y-m-d H:i:s', $response->current_period_end);
        $checkSubscription->save();
			} else {
				$checkSubscription->cancelled = 'yes';
		    $checkSubscription->save();
			}

			return redirect('account/subscription')->withSuccessCancel(__('misc.subscription_canceled_success'));
		}

}
