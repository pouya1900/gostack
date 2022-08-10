<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PaymentGateways;
use Illuminate\Support\Facades\DB;
use Stripe\StripeClient;
use Stripe\Exception\ApiErrorException;
use Illuminate\Support\Str;

class StripeConnectController extends Controller
{
  public function redirectToStripe()
  {
    $payment = PaymentGateways::whereName('Stripe')->first();
    $stripe = new StripeClient($payment->key_secret);

      $user = User::findOrFail(auth()->id());

      // Complete the onboarding process
      if (! $user->completed_stripe_onboarding) {

          $token = Str::random();

          DB::table('stripe_state_tokens')->insert([
              'created_at' => now(),
              'updated_at' => now(),
              'user_id'  => $user->id,
              'token'    => $token
          ]);

          try {

              // Let's check if they have a stripe connect id
              if (is_null($user->stripe_connect_id)) {

                  // Create account
                  $account = $stripe->accounts->create([
                      'country' => $user->country()->country_code,
                      'type'    => 'express',
                      'email'   => $user->email,
                  ]);

                  $user->update(['stripe_connect_id' => $account->id]);
                  $user->fresh();
              }

              $onboardLink = $stripe->accountLinks->create([
                  'account'     => $user->stripe_connect_id,
                  'refresh_url' => route('redirect.stripe'),
                  'return_url'  => route('save.stripe', ['token' => $token]),
                  'type'        => 'account_onboarding'
              ]);

              return redirect($onboardLink->url);

          } catch (\Exception $exception){
              return back()->withError($exception->getMessage()) ;
          }
      }

      try {

          $loginLink = $stripe->accounts->createLoginLink($user->stripe_connect_id);
          return redirect($loginLink->url);

      } catch (\Exception $exception){
          return back()->withError($exception->getMessage()) ;
      }
  }


  public function saveStripeAccount($token)
  {
      $stripeToken = DB::table('stripe_state_tokens')
                      ->where('token', '=', $token)
                      ->first();

      if (is_null($stripeToken)) {
          abort(404);
      }

      $user = User::find($stripeToken->user_id);

      $user->update([
          'completed_stripe_onboarding' => true
      ]);

      return redirect('user/dashboard/withdrawals/configure')->withSuccess(__('misc.stripe_connect_setup_success'));
  }
}
