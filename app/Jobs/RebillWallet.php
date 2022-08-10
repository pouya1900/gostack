<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Http\Controllers\Traits\FunctionsTrait;
use App\Models\Subscriptions;
use App\Models\Plans;
use App\Models\TaxRates;
use App\Helper;

class RebillWallet implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, FunctionsTrait;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      $subscriptions = Subscriptions::where('ends_at', '<', now())
      ->whereRebillWallet('on')
      ->whereCancelled('no')
      ->get();

      if ($subscriptions) {

        foreach ($subscriptions as $subscription) {

          // Get price of Plan
          $plan = Plans::wherePlanId($subscription->stripe_price)->first();

          // Get Taxes
          $taxes = TaxRates::whereIn('id', collect(explode('_', $subscription->taxes)))->get();
          $originalPlanPrice = $subscription->interval == 'month' ? $plan->price : $plan->price_year;
          $totalTaxes = ($originalPlanPrice * $taxes->sum('percentage') / 100);
          $planPrice = ($originalPlanPrice + $totalTaxes);

          if ($subscription->user()->funds >= $planPrice) {

            // Create Invoice
      			$this->invoiceSubscription($subscription->user_id, $subscription->id, $originalPlanPrice, $subscription->taxes, true);

            // Subtract user funds
            $subscription->user()->decrement('funds', $planPrice);

            // Downloads per month
            if ($plan->unused_downloads_rollover) {
              $subscription->user()->increment('downloads', $plan->downloads_per_month);
            } else {
              $subscription->user()->update(['downloads' => $plan->downloads_per_month]);
            }

            $subscription->update([
      						'ends_at' => Helper::planInterval($subscription->interval)
      					]);
          } else {
            // Remove downloads
            $subscription->user()->update(['downloads' => 0]);
          }
        }
      }

    }
}
