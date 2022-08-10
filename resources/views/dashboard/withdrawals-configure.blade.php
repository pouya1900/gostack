@extends('layouts.app')

@section('title') {{ trans('misc.payout_method') }} - @endsection

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

            	 @if (session('error'))
			<div class="alert alert-danger alert-dismissible fade show" role="alert">
            		{{ session('error') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                  <i class="bi bi-x-lg"></i>
                </button>
            		</div>
            	@endif

			@include('errors.errors-forms')

      <h5 class="mb-4">{{ trans('misc.payout_method') }}
        <small class="w-100 d-block text-muted fw-light mt-1">
          {{ trans('misc.default_withdrawal') }}: @if (auth()->user()->payment_gateway == '') {{trans('misc.unconfigured')}} @else {{auth()->user()->payment_gateway}} @endif
        </small>
      </h5>

      @if ($settings->payout_method_paypal)
      <h6 class="mb-2">PayPal</h6>
		<!-- ***** FORM ***** -->
       <form action="{{ url('user/withdrawals/configure/paypal') }}" method="post" class="mb-5">

          	<input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="form-floating mb-3">
             <input type="email" class="form-control" id="input-paypal" value="{{auth()->user()->paypal_account}}" name="email_paypal" placeholder="{{ trans('misc.email_paypal') }}">
             <label for="input-paypal">{{ trans('misc.email_paypal') }}</label>
           </div>

           <div class="form-floating mb-3">
            <input type="email" class="form-control" id="input-confirm_email" name="email_paypal_confirmation" placeholder="{{ trans('misc.confirm_email') }}">
            <label for="input-confirm_email">{{ trans('misc.confirm_email') }}</label>
          </div>

           <button type="submit" class="btn w-100 btn-lg btn-custom">{{ trans('misc.save_payout_method') }}</button>
       </form><!-- ***** END FORM ***** -->
     @endif

     @if ($settings->payout_method_bank)
       <h6 class="mb-2">{{ trans('misc.bank_transfer') }}</h6>
 		<!-- ***** FORM ***** -->
        <form action="{{ url('user/withdrawals/configure/bank') }}" method="post" name="form" class="mb-5">

           	<input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="form-floating mb-3">
             <textarea class="form-control" placeholder="{{ trans('misc.bank_details') }}" name="bank" id="input-bank_details" style="height: 100px">{{ auth()->user()->bank }}</textarea>
             <label for="input-bank_details">{{ trans('misc.bank_details') }}</label>
           </div>

            <button type="submit" class="btn w-100 btn-lg btn-custom">{{ trans('misc.save_payout_method') }}</button>
        </form><!-- ***** END FORM ***** -->
      @endif

        @if ($settings->stripe_connect
            && auth()->user()->images()->whereItemForSale('sale')->count() != 0
            && isset(auth()->user()->country()->country_code)
            && in_array(auth()->user()->country()->country_code, $stripeConnectCountries)
            )
          <h6 class="m-0">Stripe Connect @if (auth()->user()->completed_stripe_onboarding) <span class="badge bg-success fw-light opacity-75">{{ __('misc.connected') }}</span> @else <span class="badge bg-danger fw-light opacity-75">{{ __('misc.not_connected') }}</span>  @endif </h6>
          <small class="d-block w-100 mb-2">{{ __('misc.stripe_connect_desc') }}</small>


            <a href="{{ route('redirect.stripe') }}" class="btn w-100 btn-lg btn-primary arrow">

              @if (! auth()->user()->completed_stripe_onboarding)
              {{ __('misc.connect_stripe_account') }}

            @else
              {{ __('misc.view_stripe_account') }}
              @endif
            </a>

        @endif



		</div><!-- /COL MD -->
  </div><!-- row -->
 </div><!-- container -->
</section>
 <!-- container wrap-ui -->
@endsection
