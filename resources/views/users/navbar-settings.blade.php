<button class="btn btn-dark mb-4 w-100 d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#navSettings" aria-expanded="false" aria-controls="collapseExample">
    <i class="bi bi-menu-down me-2"></i> {{ trans('misc.menu') }}
  </button>

  <div class="card shadow-sm mb-3 collapse d-lg-block card-settings" id="navSettings">
  <div class="list-group list-group-flush">

    <a class="list-group-item list-group-item-action d-flex justify-content-between" href="{{ url(auth()->user()->username) }}">
			<div>
				<i class="bi-person me-2"></i>
				<span>{{ trans('users.my_profile') }}</span>
			</div>

			<div>
				<i class="bi bi-chevron-right"></i>
			</div>
		</a><!-- end link -->

    @if ($settings->sell_option == 'on')
    <a class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('user/dashboard'))active @endif" href="{{ url('user/dashboard') }}">
			<div>
				<i class="bi bi-speedometer2 me-2"></i>
				<span>{{ trans('users.dashboard') }}</span>
			</div>

			<div>
				<i class="bi bi-chevron-right"></i>
			</div>
		</a><!-- end link -->
  @endif

    <a class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('user/dashboard/photos'))active @endif" href="{{ url('user/dashboard/photos') }}">
			<div>
				<i class="bi bi-images me-2"></i>
				<span>{{ trans_choice('misc.images_plural', 0) }}</span>
			</div>

			<div>
				<i class="bi bi-chevron-right"></i>
			</div>
		</a><!-- end link -->

    @if ($settings->sell_option == 'on')
    <a class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('user/dashboard/sales'))active @endif" href="{{ url('user/dashboard/sales') }}">
			<div>
				<i class="bi bi-cart me-2"></i>
				<span>{{ trans('misc.sales') }}</span>
			</div>

			<div>
				<i class="bi bi-chevron-right"></i>
			</div>
		</a><!-- end link -->
  @endif

   @if ($settings->sell_option == 'on')
    <a class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('user/dashboard/purchases'))active @endif" href="{{ url('user/dashboard/purchases') }}">
			<div>
				<i class="bi bi-bag-check me-2"></i>
				<span>{{ trans('misc.my_purchases') }}</span>
			</div>

			<div>
				<i class="bi bi-chevron-right"></i>
			</div>
		</a><!-- end link -->
  @endif

    <a class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('user/dashboard/downloads'))active @endif" href="{{ url('user/dashboard/downloads') }}">
			<div>
				<i class="bi bi-download me-2"></i>
				<span>{{ trans('misc.downloads') }}</span>
			</div>

			<div>
				<i class="bi bi-chevron-right"></i>
			</div>
		</a><!-- end link -->

    @if ($settings->sell_option == 'on')
    <a class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('account/subscription'))active @endif" href="{{ url('account/subscription') }}">
			<div>
				<i class="bi-arrow-repeat me-2"></i>
				<span>{{ trans('misc.subscription') }}</span>
			</div>

			<div>
				<i class="bi bi-chevron-right"></i>
			</div>
		</a><!-- end link -->
  @endif

    <a class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('account'))active @endif" href="{{ url('account') }}">
			<div>
				<i class="bi bi-person me-2"></i>
				<span>{{ trans('users.account_settings') }}</span>
			</div>

			<div>
				<i class="bi bi-chevron-right"></i>
			</div>
		</a><!-- end link -->

    <a class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('account/password'))active @endif" href="{{ url('account/password') }}">
			<div>
				<i class="bi bi-key me-2"></i>
				<span>{{ trans('auth.password') }}</span>
			</div>

			<div>
				<i class="bi bi-chevron-right"></i>
			</div>
		</a><!-- end link -->

    @if ($settings->referral_system == 'on')
      <a class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('my/referrals'))active @endif" href="{{ url('my/referrals') }}">
  			<div>
  				<i class="bi bi-person-plus me-2"></i>
  				<span>{{ trans('misc.referrals') }}</span>
  			</div>

  			<div>
  				<i class="bi bi-chevron-right"></i>
  			</div>
  		</a><!-- end link -->
    @endif

    @if ($settings->sell_option == 'on')
    <a class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('user/dashboard/withdrawals/configure'))active @endif" href="{{ url('user/dashboard/withdrawals/configure') }}">
			<div>
				<i class="bi bi-credit-card me-2"></i>
				<span>{{ trans('misc.payout_method') }}</span>
			</div>

			<div>
				<i class="bi bi-chevron-right"></i>
			</div>
		</a><!-- end link -->
  @endif

  @if ($settings->sell_option == 'on')
    <a class="list-group-item list-group-item-action d-flex justify-content-between @if (request()->is('user/dashboard/withdrawals'))active @endif" href="{{ url('user/dashboard/withdrawals') }}">
			<div>
				<i class="bi bi-arrow-left-right me-2"></i>
				<span>{{ trans('misc.withdrawals') }}</span>
			</div>

			<div>
				<i class="bi bi-chevron-right"></i>
			</div>
		</a><!-- end link -->
  @endif
  </div>
</div>
