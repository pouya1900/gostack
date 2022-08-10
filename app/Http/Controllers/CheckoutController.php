<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\AdminSettings;
use App\Models\Images;
use App\Models\User;
use App\Helper;
use Mail;
use Carbon\Carbon;
use App\Models\PaymentGateways;

class CheckoutController extends Controller
{
	use Traits\FunctionsTrait;

	public function __construct(AdminSettings $settings, Request $request) {
		$this->settings = $settings::first();
		$this->request = $request;
	}

    public function send()
		{
			if ($this->settings->sell_option == 'off') {
				return response()->json([
						'success' => false,
						'errors' => ['error' => trans('misc.purchase_not_allowed') ],
				]);
			}

			$image = Images::where('token_id', $this->request->token)
			->where('user_id', '<>', auth()->id())
			->firstOrFail();

			if ($this->settings->currency_position == 'right') {
				$currencyPosition =  2;
			} else {
				$currencyPosition =  null;
			}

			$messages = [
			'type.in' => trans('misc.error'),
			'license.in' => trans('misc.error')
		];

		//<---- Validation
		$validator = Validator::make($this->request->all(), [
				'type' => 'required|in:small,medium,large,vector',
				'license' => 'required|in:regular,extended',
				'payment_gateway' => 'required',
	    	], $messages);

			if ($validator->fails()) {
			        return response()->json([
					        'success' => false,
					        'errors' => $validator->getMessageBag()->toArray(),
					    ]);
			    }

					// Wallet
	        if ($this->request->payment_gateway == 'wallet') {
	          return $this->sendWallet();
	        }

					// Get name of Payment Gateway
					$payment = PaymentGateways::find($this->request->payment_gateway);

					if (! $payment) {
						return response()->json([
								'success' => false,
								'errors' => ['error' => trans('misc.payments_error')],
						]);
					}

					$routePayment = str_slug($payment->name).'.buy';

					// Send data to the payment processor
					return redirect()->route($routePayment, $this->request->except(['_token']));

		}//<--------- End Method  Send

		private function sendWallet()
		{
	    // Get Image
	    $image = Images::where('token_id', $this->request->token)->firstOrFail();

			$priceItem = $this->settings->default_price_photos ?: $image->price;

			$itemPrice = $this->priceItem($this->request->license, $priceItem, $this->request->type);

			if (auth()->user()->funds < Helper::amountGross($itemPrice)) {
				return response()->json([
          "success" => false,
          "errors" => ['error' => __('misc.not_enough_funds')]
        ]);
			}

			// Admin and user earnings calculation
      $earnings = $this->earningsAdminUser($image->user()->author_exclusive, $itemPrice, null, null);

      // Insert purchase
      $this->purchase(
        'pw_'.str_random(25),
        $image,
        auth()->id(),
        $itemPrice,
        $earnings['user'],
        $earnings['admin'],
        $this->request->type,
        $this->request->license,
        $earnings['percentageApplied'],
				'Wallet',
        auth()->user()->taxesPayable()
      );

			// Subtract user funds
			auth()->user()->decrement('funds', Helper::amountGross($itemPrice));

      return response()->json([
        "success" => true,
        'url' => url('user/dashboard/purchases')
      ]);

		}// End Method sendWallet

}
