<?php

namespace App\Http\Controllers\Traits;

use DB;
use Mail;
use App\Helper;
use App\Models\User;
use App\Models\AdminSettings;
use App\Models\Notifications;
use App\Models\Comments;
use App\Models\Like;
use App\Models\TaxRates;
use App\Models\Referrals;
use App\Models\ReferralTransactions;
use App\Models\Invoices;
use App\Models\Downloads;
use App\Models\PaymentGateways;
use App\Models\Transactions;
use App\Models\Deposits;
use App\Models\Purchases;
use App\Models\TwoFactorCodes;
use App\Notifications\SendTwoFactorCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

trait FunctionsTrait
{
	// Admin and user earnings calculation
	protected function earningsAdminUser($authorExclusive, $amount, $paymentFee, $paymentFeeCents)
	{
		$settings = AdminSettings::first();

		$feeCommission = $authorExclusive == 'yes' ? $settings->fee_commission : $settings->fee_commission_non_exclusive;

		if (isset($paymentFee)) {
			$processorFees = $amount - ($amount * $paymentFee/100) - $paymentFeeCents;

			// Earnings Net User
			$earningNetUser = $processorFees - ($processorFees * $feeCommission/100);
			// Earnings Net Admin
			$earningNetAdmin = $processorFees - $earningNetUser;
		} else {
			// Earnings Net User
      $earningNetUser = $amount - ($amount * $feeCommission/100);

      // Earnings Net Admin
      $earningNetAdmin = ($amount - $earningNetUser);
		}

		if (isset($paymentFee)) {
			$paymentFees =  $paymentFeeCents == 0.00 ? $paymentFee.'% + ' : $paymentFee.'%'.' + '.$paymentFeeCents.' + ';
		} else {
			$paymentFees = null;
		}

		// Percentage applied
		$percentageApplied = $paymentFees.$feeCommission.'%';


		if ($settings->currency_code == 'JPY') {
			$userEarning = floor($earningNetUser);
			$adminEarning = floor($earningNetAdmin);
		} else {
			$userEarning = number_format($earningNetUser, 2, '.', '');
			$adminEarning = number_format($earningNetAdmin, 2, '.', '');
		}

		return [
			'user' => $userEarning,
			'admin' => $adminEarning,
			'percentageApplied' => $percentageApplied
		];

	}// End Method

	// Insert Purchase
	protected function purchase(
		$txnId,
		$image,
		$userId,
		$priceItem,
		$userEarning,
		$adminEarning,
		$type,
		$license,
		$percentageApplied,
		$paymentGateway,
		$taxes,
		$directPayment = false,
		$approved = '1'
		) {
				// Referred
				$earningAdminReferred = $approved == '1' ? $this->referred($userId, $adminEarning, 'photo') : null;

				// Insert Purchase
				$purchase                      = new Purchases();
				$purchase->txn_id              = $txnId;
				$purchase->images_id           = $image->id;
				$purchase->user_id             = $userId;
				$purchase->price               = $priceItem;
				$purchase->earning_net_seller  = $userEarning;
				$purchase->earning_net_admin   = $earningAdminReferred ?: $adminEarning;
				$purchase->payment_gateway     = $paymentGateway;
				$purchase->type                = $type;
				$purchase->license             = $license;
				$purchase->order_id	           = substr(strtolower( md5( microtime() . mt_rand( 1000, 9999 ) ) ), 0, 15 );
				$purchase->purchase_code       = implode( '-', str_split( substr( strtolower( md5( time() . mt_rand( 1000, 9999 ) ) ), 0, 27 ), 5 ) );
				$purchase->percentage_applied  = $percentageApplied;
				$purchase->referred_commission = $earningAdminReferred ? true : false;
				$purchase->taxes               = $taxes;
				$purchase->approved            = $approved;
				$purchase->direct_payment      = $directPayment;
				$purchase->save();

				// Create invoice
				$this->invoice($userId, $purchase->id, $priceItem, $percentageApplied, $taxes, $approved);

				if ($approved == '1') {

					// Add Balance And Notify to User
					$amountUserEarning = $directPayment ? 0 : $userEarning;

					$this->AddBalanceAndNotify($image, $userId, $amountUserEarning);

					// Insert Download
					$this->downloads($image->id, $userId);
				}

				return $purchase;

		}// End Method

		protected function invoice($userId, $purchaseId, $amount, $percentageApplied, $taxes, $approved)
		{
			$invoice = new Invoices();
			$invoice->user_id = $userId;
			$invoice->purchases_id = $purchaseId;
			$invoice->amount = $amount;
			$invoice->status = $approved ? 'paid' : 'pending';
			$invoice->percentage_applied = $percentageApplied;
			$invoice->taxes = $taxes;
			$invoice->save();
		}// End Method

		protected function downloads($imageId, $userId)
		{
			$download            = new Downloads();
			$download->images_id = $imageId;
			$download->user_id   = $userId;
			$download->ip        = request()->ip();
			$download->type      = 'sale';
			$download->save();
		}// End Method

		protected function referred($userId, $adminEarning, $type)
		{
			$settings = AdminSettings::first();

			// Check Referred
			if ($settings->referral_system == 'on') {

				// Check for referred
				$referred = Referrals::whereUserId($userId)->first();

						if ($referred) {

							// Check if the user who referred exists
							$referredBy = User::find($referred->referred_by);

							if ($referredBy) {

								// Check numbers of transactions
								$transactions = ReferralTransactions::whereUserId($userId)->count();

								if ($settings->referral_transaction_limit == 'unlimited'
										|| $transactions < $settings->referral_transaction_limit
									) {

										$adminEarningFinal = $adminEarning - ($adminEarning * $settings->percentage_referred/100);

										$earningNetUser = ($adminEarning - $adminEarningFinal);
										$adminEarning   = ($adminEarning - $earningNetUser);

										if ($settings->currency_code == 'JPY') {
											$earningNetUser = floor($earningNetUser);
											$adminEarning   = floor($adminEarning);
										} else {
											$earningNetUser = round($earningNetUser, 2, PHP_ROUND_HALF_DOWN);
											$adminEarning   = round($adminEarning, 2, PHP_ROUND_HALF_DOWN);
										}

										if ($earningNetUser != 0) {
											// Insert User Earning
											$newTransaction = new ReferralTransactions();
											$newTransaction->referrals_id = $referred->id;
											$newTransaction->user_id = $referred->user_id;
											$newTransaction->referred_by = $referred->referred_by;
											$newTransaction->earnings = $earningNetUser;
											$newTransaction->type = $type;
											$newTransaction->save();

											// Add Earnings to User
											$referred->referredBy()->increment('balance', $earningNetUser);

											// Notify to user - destination, author, type, target
											Notifications::send($referred->referred_by, $referred->referred_by, 6, $referred->referred_by);

											return $adminEarning;
										}
								}
							}//=== $referredBy
						}// $referred
			}// referral_system On

			return false;
		}// End Method

	// Insert Deposit (Add funds user wallet)
	protected function deposit($userId, $txnId, $amount, $paymentGateway, $taxes, $status = 'active')
	{
		$payment = PaymentGateways::whereName($paymentGateway)->firstOrFail();
		$paymentFee = $payment->fee;
		$paymentFeeCents = $payment->fee_cents;

		// Percentage applied
		$percentageApplied =  $paymentFeeCents == 0.00 ? $paymentFee.'%' : $paymentFee.'%'.' + '.$paymentFeeCents;

		// Percentage applied amount
		$transactionFeeAmount = number_format($amount + ($amount * $paymentFee / 100) + $paymentFeeCents, 2, '.', '');
		$transactionFee = ($transactionFeeAmount - $amount);

		$sql = new Deposits();
		$sql->user_id = $userId;
		$sql->txn_id = $txnId;
		$sql->amount = $amount;
		$sql->payment_gateway = $paymentGateway;
		$sql->status = $status;
		$sql->save();

		$this->invoiceDeposits($userId, $sql->id, $amount, $percentageApplied, $transactionFee, $taxes, $status);

		return $sql;

	}// End Method

	protected function invoiceDeposits($userId, $depositId, $amount, $percentageApplied, $transactionFee, $taxes, $status)
	{
		$invoice = new Invoices();
		$invoice->user_id = $userId;
		$invoice->deposits_id = $depositId;
		$invoice->amount = $amount;
		$invoice->status = $status == 'active' ? 'paid' : 'pending';
		$invoice->percentage_applied = $percentageApplied;
		$invoice->transaction_fee = $transactionFee;
		$invoice->taxes = $taxes;
		$invoice->save();
	}// End Method

	protected function invoiceSubscription($userId, $subscriptionId, $amount, $taxes, $approved)
	{
		$invoice = new Invoices();
		$invoice->user_id = $userId;
		$invoice->subscriptions_id = $subscriptionId;
		$invoice->amount = $amount;
		$invoice->status = $approved ? 'paid' : 'pending';
		$invoice->taxes = $taxes;
		$invoice->save();
	}// End Method

	protected function AddBalanceAndNotify($data, $userId, $userEarning)
	{
		// Add user balance
		$data->user()->increment('balance', $userEarning);

		// Send Notification - destination, author, type, target
		Notifications::send($data->user()->id, $userId, 5, $data->id);
	}// End Method

	protected function generateTwofaCode($user)
  {
    $code = rand(1000, 9999);

    // Delete old session user id
    session()->forget('user:id');

    // Create session user
    session()->put('user:id', $user->id);

        TwoFactorCodes::updateOrCreate([
          'user_id' => $user->id,
          'code' => $code
        ]);

        try {
            $data = ['code' => $code];

            $user->notify(new SendTwoFactorCode($data));

        } catch (Exception $e) {
            \Log::info("Error: ". $e->getMessage());
        }
  }// End method

	protected function createTaxStripe($id, $name, $country, $stateCode, $percentage)
	{
		$payment = PaymentGateways::whereName('Stripe')
			->whereEnabled('1')
			->where('key_secret', '<>', '')
			->first();

			if ($payment) {
				try {
					$stripe = new \Stripe\StripeClient($payment->key_secret);

					if ($stateCode) {
						$tax = $stripe->taxRates->create([
							'display_name' => $name,
							'description' => $name.' - '.$country->country_name,
							'country' => $country->country_code,
							'jurisdiction' => $country->country_code,
							'state' => $stateCode,
							'percentage' => $percentage,
							'inclusive' => false,
						]);
					} else {
						$tax = $stripe->taxRates->create([
							'display_name' => $name,
							'description' => $name.' - '.$country->country_name,
							'country' => $country->country_code,
							'jurisdiction' => $country->country_code,
							'percentage' => $percentage,
							'inclusive' => false,
						]);
					}

					// Insert ID to tax_rates table
					TaxRates::whereId($id)->update([
						'stripe_id' => $tax->id
					]);


				} catch (\Exception $e) {
					\Log::debug($e->getMessage());
				}
			}
	}// End method

	protected function updateTaxStripe($stripe_id, $name, $status)
	{
		$payment = PaymentGateways::whereName('Stripe')
			->whereEnabled('1')
			->where('key_secret', '<>', '')
			->first();

			if ($payment) {
				try {
					$stripe = new \Stripe\StripeClient($payment->key_secret);

					$stripe->taxRates->update($stripe_id,
					['active' => $status ? 'true' : 'false',
					'display_name' => $name
					]);

				} catch (\Exception $e) {
					\Log::debug($e->getMessage());
				}
			}
	}// End method

	protected function priceItem($license, $price, $type)
	{
		if ($license == 'extended') {
			 $price = ($price*10);
		}

		switch ($type) {
		case 'small':
			$priceItem = $price;
			break;
		case 'medium':
			$priceItem = ($price*2);
			break;
		case 'large':
			$priceItem = ($price*3);
			break;
		case 'vector':
			$priceItem = ($price*4);
				break;
			}

			return $priceItem;
	}// End method


}// End Class
