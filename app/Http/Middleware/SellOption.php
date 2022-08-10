<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use App\Models\AdminSettings;

class SellOption
{

	/**
	 * The Guard implementation.
	 *
	 * @var Guard
	 */
	protected $auth;

	/**
	 * Create a new filter instance.
	 *
	 * @param  Guard  $auth
	 * @return void
	 */
	public function __construct(Guard $auth)
	{
		$this->auth = $auth;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
    $settings = AdminSettings::first();

		if ($settings->sell_option == 'off'
        && $request->is('user/dashboard')

        || $settings->sell_option == 'off'
        && $request->is('user/dashboard/sales')

				|| $settings->sell_option == 'off'
				&& $request->is('user/dashboard/purchases')

				|| $settings->sell_option == 'off'
				&& $request->is('user/dashboard/withdrawals/configure')

				|| $settings->sell_option == 'off'
				&& $request->is('user/dashboard/withdrawals')

				|| $settings->sell_option == 'off'
				&& $request->is('user/dashboard/add/funds')

      ) {
      abort(404);
		}

		return $next($request);
	}

}
