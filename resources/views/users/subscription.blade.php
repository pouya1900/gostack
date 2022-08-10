@extends('layouts.app')

@section('title') {{ trans('misc.subscription') }} - @endsection

@section('content')
<section class="section section-sm">

<div class="container-custom container py-5">
  <div class="row">

    <div class="col-md-3">
      @include('users.navbar-settings')
    </div>

		<!-- Col MD -->
		<div class="col-md-9">

			@if (session('success'))
			<div class="alert alert-success alert-dismissible fade show" role="alert">
    		<i class="bi bi-check2 me-1"></i>	{{ session('success') }}

        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
          <i class="bi bi-x-lg"></i>
        </button>
    		</div>
      @endif

      @if (session('success_with_alert'))
			<div class="alert alert-success alert-dismissible fade show" role="alert">
    		<i class="bi bi-check2 me-1"></i>	{{ session('success_with_alert') }}

        <small class="d-block w-100">{{ __('misc.notify_subscription') }}</small>

        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
          <i class="bi bi-x-lg"></i>
        </button>
    		</div>
      @endif

      @if (session('successCancel'))
			<div class="alert alert-success alert-dismissible fade show" role="alert">
    		<i class="bi bi-check2 me-1"></i>	{{ session('successCancel') }}

        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
          <i class="bi bi-x-lg"></i>
        </button>
    		</div>
      @endif

      @if (session('error'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi-exclamation-triangle me-1"></i>	{{ session('error') }}

        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
          <i class="bi bi-x-lg"></i>
        </button>
        </div>
      @endif



  <h5 class="mb-4">{{ trans('misc.subscription') }}</h5>

	@if ($subscription)
  <div class="card">
    <div class="card-body">
      <small>{{ __('misc.plan') }}</small>
      <h6>{{ $subscription->plan->name }}</h6>

      <small>{{ __('misc.billed') }}</small>
      <h6>{{ $subscription->interval == 'month' ? __('misc.monthly') : __('misc.yearly') }}</h6>

      <small>{{ __('misc.ends_at') }}</small>
      <h6>
        @if ($subscription->ends_at)
          {{ Helper::formatDate($subscription->ends_at) }}
        @else
          {{ Helper::formatDate(auth()->user()->subscription('main', $subscription->stripe_price)->asStripeSubscription()->current_period_end, true) }}
        @endif
      </h6>

      @if (auth()->user()->getSubscription())
      <small>{{ __('misc.available_downloads') }}</small>
      <h6>{{ auth()->user()->downloads }}</h6>

      @if ($subscription->plan->download_limits)
        <small>{{ __('misc.limit_daily_downloads_available') }}</small>
        <h6>{{ $subscription->plan->download_limits - auth()->user()->subscriptionDailyDownloads() }}</h6>
      @endif

      @endif

      @if (auth()->user()->getSubscription()
          && $subscription->stripe_status == 'active'
          && $subscription->cancelled == 'no'
          || auth()->user()->getSubscription()
          && ! $subscription->stripe_id
          && $subscription->cancelled == 'no'
          )
        <form action="{{ url('account/subscription/cancel') }}" method="post" class="formCancel">
          @csrf
          <input type="hidden" name="id" value="{{ $subscription->id }}">
          <button data-alert="{{ __('misc.alert_subscription_cancel') }}" class="btn btn-success rounded-pill cancelBtn subscriptionActive" type="button">
            <i class="bi-check2 me-1"></i> {{ __('misc.your_subscribed') }}
          </button>
        </form>

      @else
        <small>{{ __('misc.status') }}</small>
        <h6 class="text-danger">{{ $subscription->cancelled == 'no' && $subscription->stripe_status != 'canceled' ? __('misc.expired') : __('misc.cancelled') }}</h6>
      @endif

    </div>
  </div>

  @else
    <h3 class="mt-0 fw-light">
      {{ trans('misc.not_subscribed') }}
    </h3>
  @endif

  @if ($subscriptions->count() != 0)
  <h6 class="text-center mt-5 font-weight-light">{{ __('misc.invoices') }}</h6>

  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table m-0">
        <thead>
          <th scope="col">ID</th>
          <th scope="col">{{ trans('admin.amount') }}</th>
          <th scope="col">{{ trans('admin.date') }}</th>
          <th scope="col">{{ trans('admin.status') }}</th>
          <th> {{trans('misc.invoice')}}</th>
        </thead>

        <tbody>
          @foreach ($subscriptions as $subscription)

            <tr>
              <td>{{ str_pad($subscription->invoice()->id, 4, "0", STR_PAD_LEFT) }}</td>
              <td>{{ Helper::amountFormat($subscription->invoice()->amount) }}</td>
              <td>{{ date('d M, Y', strtotime($subscription->invoice()->created_at)) }}</td>
              <td><small class="badge rounded-pill bg-success text-uppercase">{{ trans('misc.paid') }}</small></td>

               <td>
                 <a href="{{url('invoice', $subscription->invoice()->id)}}" target="_blank"><i class="bi-receipt"></i> {{trans('misc.invoice')}}</a>
                 </td>
            </tr><!-- /.TR -->
            @endforeach
        </tbody>
      </table>
    </div><!-- table-responsive -->
  </div><!-- card -->

  @if ($subscriptions->hasPages())
      <div class="mt-3">
        {{ $subscriptions->links() }}
      </div>
      @endif

@endif

		</div><!-- /COL MD -->
  </div><!-- row -->
 </div><!-- container -->
</section>
 <!-- container wrap-ui -->
@endsection

@section('javascript')
<script src="{{ asset('public/js/subscription.js') }}?v={{$settings->version}}"></script>
@endsection
