@if (auth()->user()->role && ! request()->is('panel/admin') && ! request()->is('panel/admin/*'))
  <li><a class="dropdown-item" href="{{ url('panel/admin') }}"><i class="bi bi-speedometer2 me-2"></i> {{ trans('admin.admin') }}</a></li>
  <li><hr class="dropdown-divider"></li>
@endif

@if ($settings->sell_option == 'on')
<li>
  <span class="dropdown-item disable-item">
    <i class="bi bi-cash-stack me-2"></i> {{ trans('misc.balance') }}: {{ Helper::amountFormatDecimal(auth()->user()->balance) }}</span>
  </li>

<li>
<a class="dropdown-item" href="{{ url('user/dashboard/add/funds') }}">
  <i class="bi bi-wallet2 me-2"></i> {{ trans('misc.wallet') }}: {{ Helper::amountFormatDecimal(auth()->user()->funds) }}
</a>
</li>
@endif

@if ($settings->daily_limit_downloads != 0 && auth()->user()->role != 'admin')
    <li>
        <span class="dropdown-item disable-item">
        <i class="bi bi-download me-2"></i> {{ trans('misc.downloads') }}: {{ auth()->user()->freeDailyDownloads() }}/{{ $settings->daily_limit_downloads }}
    </span>
    </li>
@endif

@if ($settings->sell_option == 'on')
  <li>
  <a class="dropdown-item" href="{{ url('user/dashboard') }}">
      <i class="bi bi-speedometer2 me-2"></i> {{ trans('admin.dashboard') }}
      </a>
  </li>
@endif

<li>
<a class="dropdown-item" href="{{ url(auth()->user()->username) }}">
    <i class="bi bi-person me-2"></i> {{ trans('users.my_profile') }}
    </a>
</li>

@if ($settings->sell_option == 'on')
<li>
<a class="dropdown-item" href="{{ url('account/subscription') }}">
    <i class="bi-arrow-repeat me-2"></i> {{ trans('misc.subscription') }}
    </a>
</li>

<li>
<a class="dropdown-item" href="{{ url('user/dashboard/purchases') }}">
    <i class="bi-bag-check me-2"></i> {{ trans('misc.my_purchases') }}
    </a>
</li>
@endif

<li>
<a class="dropdown-item" href="{{ url(auth()->user()->username, 'collections') }}">
    <i class="bi bi-plus-square me-2"></i> {{ trans('misc.collections') }}
    </a>
</li>

<li>
<a class="dropdown-item" href="{{ url('likes') }}">
    <i class="bi bi-heart me-2"></i> {{ trans('users.likes') }}
    </a>
</li>

@if ($settings->referral_system == 'on')
<li>
<a class="dropdown-item" href="{{ url('my/referrals') }}">
    <i class="bi-person-plus me-2"></i> {{ trans('misc.referrals') }}
    </a>
</li>
@endif

<li>
<a class="dropdown-item" href="{{ url('account') }}">
    <i class="bi bi-gear me-2"></i> {{ trans('users.account_settings') }}
    </a>
</li>

<li><hr class="dropdown-divider"></li>
<li>
  <a class="dropdown-item" href="{{ url('logout') }}">
    <i class="bi bi-box-arrow-in-right me-2"></i> {{ trans('users.logout') }}</a>
  </li>
