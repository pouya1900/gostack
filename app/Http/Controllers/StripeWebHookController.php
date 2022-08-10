<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdminSettings;
use App\Models\Notifications;
use App\Models\Plans;
use Illuminate\Http\Response;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Http\Controllers\WebhookController;
use Laravel\Cashier\Subscription;
use App\Models\PaymentGateways;
use App\Models\Transactions;
use App\Models\Deposits;
use App\Models\Images;
use Stripe\PaymentIntent as StripePaymentIntent;
use App\Models\User;

class StripeWebHookController extends WebhookController
{
  use Traits\FunctionsTrait;

    /**
     *
     * customer.subscription.deleted
     *
     * @param array $payload
     * @return Response|\Symfony\Component\HttpFoundation\Response
     */
    public function handleCustomerSubscriptionDeleted(array $payload) {
        $user = $this->getUserByStripeId($payload['data']['object']['customer']);
        if ($user) {
            $user->subscriptions->filter(function ($subscription) use ($payload) {
                return $subscription->stripe_id === $payload['data']['object']['id'];
            })->each(function ($subscription) {
                $subscription->markAsCancelled();
            });
        }
        return new Response('Webhook Handled', 200);
    }

    /**
     *
     * WEBHOOK Insert the information of each payment in the Payments table when successfully generating an invoice in Stripe
     *
     * @param array $payload
     * @return Response|\Symfony\Component\HttpFoundation\Response
     */
    public function handleInvoicePaymentSucceeded($payload)
    {
        try {
            $settings = AdminSettings::first();
            $data     = $payload['data'];
            $object   = $data['object'];
            $customer = $object['customer'];
            $amount   = $settings->currency_code == 'JPY' ? $object['subtotal'] : ($object['subtotal'] / 100);
            $user     = $this->getUserByStripeId($customer);
            $interval = $object['lines']['data'][0]['metadata']['interval'] ?? 'month';
            $taxes    = $object['lines']['data'][0]['metadata']['taxes'] ?? null;

            if ($user) {
                $subscription = Subscription::whereStripeId($object['subscription'])->first();
                if ($subscription) {
                  $subscription->stripe_status = "active";
                  $subscription->interval = $interval;
                  $subscription->payment_gateway = 'Stripe';

                  // Get data Plan
                  $plan = Plans::wherePlanId($subscription->stripe_price)->first();

                if ($object['billing_reason'] == 'subscription_create') {
                    User::find($subscription->user_id)->update(['downloads' => $plan->downloads_per_month]);
                  }

                    // Renewal cycle
                    if ($object['billing_reason'] == 'subscription_cycle') {
                      if ($plan->unused_downloads_rollover) {
                        User::find($subscription->user_id)->increment('downloads', $plan->downloads_per_month);
                      } else {
                        User::find($subscription->user_id)->update(['downloads' => $plan->downloads_per_month]);
                      }
                    }

                    // Save subscription
                    $subscription->save();

                    // Create Invoice
                    $this->invoiceSubscription($subscription->user_id, $subscription->id, $amount, $taxes, true);

                }
                return new Response('Webhook Handled: {handleInvoicePaymentSucceeded}', 200);
            }
            return new Response('Webhook Handled but user not found: {handleInvoicePaymentSucceeded}', 200);
        } catch (\Exception $exception) {
            Log::debug($exception->getMessage());
            return new Response('Webhook Unhandled: {handleInvoicePaymentSucceeded}', $exception->getCode());
        }
    }

    /**
     *
     * checkout.session.completed
     *
     * @param array $payload
     * @return Response|\Symfony\Component\HttpFoundation\Response
     */
    public function handleCheckoutSessionCompleted($payload)
    {
        try {
            $settings = AdminSettings::first();
            $data     = $payload['data'];
            $object   = $data['object'];
            $userId   = $object['metadata']['userId'] ?? null;
            $amount   = $object['metadata']['amount'] ?? null;

            // Buy data
            $token   = $object['metadata']['token'] ?? null;
            $license   = $object['metadata']['license'] ?? null;
            $type   = $object['metadata']['type'] ?? null;

            $taxes    = $object['metadata']['taxes'] ?? null;
            $mode     = $object['metadata']['mode'] ?? null;

            if (! isset($mode)) {
              return new Response('Webhook Handled with error: type transaction not defined', 500);
            }

            // Add funds (Deposit)
            if (isset($mode) && $mode == 'deposit') {
              if ($object['payment_status'] == 'paid' && isset($userId)) {
                $amount_total = $object['amount_total'] / 100;

                if (isset($amount) && $amount_total >= $amount) {

                  // Check transaction
      						$verifiedTxnId = Deposits::where('txn_id', $object['payment_intent'])->first();

                  if (! $verifiedTxnId) {
                    // Insert Deposit
                    $this->deposit($userId, $object['payment_intent'], $amount, 'Stripe', $taxes);

                    // Add Funds to User
                    User::find($userId)->increment('funds', $amount);
                  }
                }
              }
            }// End Add funds

            // Buy
            if (isset($mode) && $mode == 'sale') {
              if ($object['payment_status'] == 'paid' && isset($userId)) {
                $amount_total = $object['amount_total'] / 100;

                //= Processor Fees
                $payment = PaymentGateways::whereName('Stripe')->first();

                // Get Image
                $image = Images::where('token_id', $token)->firstOrFail();

                  // Price Item
                  $priceItem = $settings->default_price_photos ?: $image->price;

                  $itemPrice = $this->priceItem($license, $priceItem, $type);

                  // Admin and user earnings calculation
                  $earnings = $this->earningsAdminUser($image->user()->author_exclusive, $itemPrice, $payment->fee, $payment->fee_cents);

                  // Stripe Connect
                  if ($image->user()->stripe_connect_id && $image->user()->completed_stripe_onboarding) {
                    try {
                      // Stripe Client
                      $stripe = new \Stripe\StripeClient($payment->key_secret);

                      $earningsUser = $settings->currency_code == 'JPY' ? $earnings['user'] : ($earnings['user']*100);

                      $stripe->transfers->create([
                        'amount' => $earningsUser,
                        'currency' => $settings->currency_code,
                        'destination' => $image->user()->stripe_connect_id,
                        'description' => trans('misc.stock_photo_purchase')
                      ]);

                      $directPayment = true;

                    } catch (\Exception $e) {
                      Log::info($e->getMessage());
                    }
                  }

                  // Insert purchase
                  $this->purchase(
                    $object['payment_intent'],
                    $image,
                    $userId,
                    $itemPrice,
                    $earnings['user'],
                    $earnings['admin'],
                    $type,
                    $license,
                    $earnings['percentageApplied'],
                    'Stripe',
                    $taxes,
                    $directPayment ?? false
                  );
               }
            }// End Buy

            return new Response('Webhook Handled: {handleInvoicePaymentSucceeded}', 200);

        } catch (\Exception $exception) {
            Log::debug($exception->getMessage());
            return new Response('Webhook Unhandled: {handleInvoicePaymentSucceeded}', $exception->getCode());
        }
    }

    /**
     *
     * charge.refunded
     *
     * @param array $payload
     * @return Response|\Symfony\Component\HttpFoundation\Response
     */
    public function handleChargeRefunded($payload)
    {
        try {
          $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
          $stripe->subscriptions->cancel($payload['data']['object']['subscription'], []);

          return new Response('Webhook Handled: {handleChargeRefunded}', 200);

        } catch (\Exception $exception) {
            Log::debug("Exception Webhook {handleChargeRefunded}: " . $exception->getMessage() . ", Line: " . $exception->getLine() . ', File: ' . $exception->getFile());
            return new Response('Webhook Handled with error: {handleChargeRefunded}', 400);
        }
    }

    /**
     * WEBHOOK Manage the SCA by notifying the user by email
     *
     * @param  array  $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleInvoicePaymentActionRequired(array $payload)
    {
        $subscription = Subscription::whereStripeId($payload['data']['object']['subscription'])->first();
        if ($subscription) {
            $subscription->stripe_status = "incomplete";
            $subscription->last_payment = $payload['data']['object']['payment_intent'];
            $subscription->save();
        }

        if (is_null($notification = config('cashier.payment_notification'))) {
            return $this->successMethod();
        }

        if ($user = $this->getUserByStripeId($payload['data']['object']['customer'])) {
            if (in_array(Notifiable::class, class_uses_recursive($user))) {
              $payment = new \Laravel\Cashier\Payment(Cashier::stripe()->paymentIntents->retrieve(
                  $payload['data']['object']['payment_intent']
              ));

                $user->notify(new $notification($payment));
            }
        }
        return $this->successMethod();
    }
}
