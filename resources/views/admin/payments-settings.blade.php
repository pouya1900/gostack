@extends('admin.layout')

@section('css')
<link href="{{ asset('public/js/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('public/js/select2/select2-bootstrap-5-theme.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ __('misc.payment_settings') }}</span>
  </h5>

<div class="content">
	<div class="row">

		<div class="col-lg-12">

			@if (session('success_message'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
              <i class="bi bi-check2 me-1"></i>	{{ session('success_message') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                  <i class="bi bi-x-lg"></i>
                </button>
                </div>
              @endif

              @include('errors.errors-forms')

			<div class="card shadow-custom border-0">
				<div class="card-body p-lg-5">

					 <form method="POST" action="{{ url('panel/admin/payments') }}" enctype="multipart/form-data">
             @csrf

            <div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">{{ trans('misc.currency_code') }}</label>
		          <div class="col-sm-10">
		            <input value="{{ $settings->currency_code }}" name="currency_code" type="text" class="form-control">
		          </div>
		        </div>

            <div class="row mb-3">
              <label class="col-sm-2 col-form-label text-lg-end">{{ trans('misc.currency_symbol') }}</label>
              <div class="col-sm-10">
                <input value="{{ $settings->currency_symbol }}" name="currency_symbol" type="text" class="form-control">
                <small class="d-block">{{ __('misc.notice_currency') }}</small>
              </div>
            </div>

		        <div class="row mb-3">
		          <label class="col-sm-2 col-form-labe text-lg-end">{{ trans('misc.fee_commission') }} {{ trans('misc.exclusive_author') }}</label>
		          <div class="col-sm-10">
		            <select name="fee_commission" class="form-select">
                  @for ($i=1; $i <= 95; ++$i)
                    <option @if ($settings->fee_commission == $i) selected="selected" @endif value="{{$i}}">{{$i}}%</option>
                    @endfor
		           </select>
		          </div>
		        </div>

            <div class="row mb-3">
		          <label class="col-sm-2 col-form-labe text-lg-end">{{ trans('misc.fee_commission') }} {{ trans('misc.non_exclusive_author') }}</label>
		          <div class="col-sm-10">
		            <select name="fee_commission_non_exclusive" class="form-select">
                  @for ($i=1; $i <= 95; ++$i)
                    <option @if ($settings->fee_commission_non_exclusive == $i) selected="selected" @endif value="{{$i}}">{{$i}}%</option>
                    @endfor
		           </select>
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-labe text-lg-end">{{ trans('misc.percentage_referred') }}</label>
		          <div class="col-sm-10">
		            <select name="percentage_referred" class="form-select">
                  @for ($i=2; $i <= 10; ++$i)
                    <option @if ($settings->percentage_referred == $i) selected="selected" @endif value="{{$i}}">{{$i}}%</option>
                    @endfor
		           </select>
		          </div>
		        </div>

						<div class="row mb-3">
							<label class="col-sm-2 col-form-labe text-lg-end">{{ trans('misc.referral_transaction_limit') }}</label>
							<div class="col-sm-10">
								<select name="referral_transaction_limit" class="form-select">
									<option @if ($settings->referral_transaction_limit == 'unlimited') selected="selected" @endif value="unlimited">
										{{ trans('admin.unlimited') }}
									</option>

									@for ($i=1; $i <= 100; ++$i)
										<option @if ($settings->referral_transaction_limit == $i) selected="selected" @endif value="{{$i}}">{{$i}}</option>
										@endfor
							 </select>
							</div>
						</div>

						<div class="row mb-3">
              <label class="col-sm-2 col-form-label text-lg-end">{{ trans('misc.default_price_photos') }}</label>
              <div class="col-sm-10">
                <input value="{{ $settings->default_price_photos }}" name="default_price_photos" type="number" autocomplete="off" class="form-control">
								<small class="d-block">{{ __('misc.info_default_price_photos') }}</small>
              </div>
            </div>

						<div class="row mb-3">
              <label class="col-sm-2 col-form-label text-lg-end">{{ trans('misc.min_sale_amount') }}</label>
              <div class="col-sm-10">
                <input value="{{ $settings->min_sale_amount }}" name="min_sale_amount" type="number" min="1" autocomplete="off" class="form-control">
              </div>
            </div>

            <div class="row mb-3">
              <label class="col-sm-2 col-form-label text-lg-end">{{ trans('misc.max_sale_amount') }}</label>
              <div class="col-sm-10">
                <input value="{{ $settings->max_sale_amount }}" name="max_sale_amount" type="number" min="1" autocomplete="off" class="form-control">
              </div>
            </div>

            <div class="row mb-3">
              <label class="col-sm-2 col-form-label text-lg-end">{{ trans('misc.min_deposits_amount') }}</label>
              <div class="col-sm-10">
                <input value="{{ $settings->min_deposits_amount }}" name="min_deposits_amount" type="number" min="1" autocomplete="off" class="form-control">
              </div>
            </div>

            <div class="row mb-3">
              <label class="col-sm-2 col-form-label text-lg-end">{{ trans('misc.max_deposits_amount') }}</label>
              <div class="col-sm-10">
                <input value="{{ $settings->max_deposits_amount }}" name="max_deposits_amount" type="number" min="1" autocomplete="off" class="form-control">
              </div>
            </div>

            <div class="row mb-3">
              <label class="col-sm-2 col-form-label text-lg-end">{{ trans('misc.amount_min_withdrawal') }}</label>
              <div class="col-sm-10">
                <input value="{{ $settings->amount_min_withdrawal }}" name="amount_min_withdrawal" type="number" min="1" autocomplete="off" class="form-control">
              </div>
            </div>

            <div class="row mb-3">
		          <label class="col-sm-2 col-form-labe text-lg-end">{{ trans('misc.currency_position') }}</label>
		          <div class="col-sm-10">
		            <select name="currency_position" class="form-select">
                  <option @if ($settings->currency_position == 'left') selected="selected" @endif value="left">{{$settings->currency_symbol}}99 - {{trans('misc.left')}}</option>
                  <option @if ($settings->currency_position == 'right') selected="selected" @endif value="right">99{{$settings->currency_symbol}} {{trans('misc.right')}}</option>
		           </select>
		          </div>
		        </div>

            <div class="row mb-3">
		          <label class="col-sm-2 col-form-labe text-lg-end">{{ trans('misc.decimal_format') }}</label>
		          <div class="col-sm-10">
		            <select name="decimal_format" class="form-select">
                  <option @if ($settings->decimal_format == 'dot') selected="selected" @endif value="dot">1,999.95</option>
                  <option @if ($settings->decimal_format == 'comma') selected="selected" @endif value="comma">1.999,95</option>
                </select>
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-labe text-lg-end">{{ __('misc.payout_method') }} (PayPal)</label>
		          <div class="col-sm-10">
		            <select name="payout_method_paypal" class="form-select">
                  <option @if ($settings->payout_method_paypal) selected="selected" @endif value="1">{{ __('misc.enabled') }}</option>
                  <option @if (! $settings->payout_method_paypal) selected="selected" @endif value="0">{{ __('misc.disabled') }}</option>
                </select>
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-labe text-lg-end">{{ __('misc.payout_method') }} ({{ __('misc.bank_transfer') }})</label>
		          <div class="col-sm-10">
		            <select name="payout_method_bank" class="form-select">
                  <option @if ($settings->payout_method_bank) selected="selected" @endif value="1">{{ __('misc.enabled') }}</option>
                  <option @if (! $settings->payout_method_bank) selected="selected" @endif value="0">{{ __('misc.disabled') }}</option>
                </select>
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-labe text-lg-end">{{ __('misc.apply_taxes_wallet') }}</label>
		          <div class="col-sm-10">
		            <select name="tax_on_wallet" class="form-select">
                  <option @if ($settings->tax_on_wallet) selected="selected" @endif value="1">{{ __('misc.enabled') }}</option>
                  <option @if (! $settings->tax_on_wallet) selected="selected" @endif value="0">{{ __('misc.disabled') }}</option>
                </select>
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-labe text-lg-end">Stripe Connect</label>
		          <div class="col-sm-10">
		            <select name="stripe_connect" class="form-select">
                  <option @if ($settings->stripe_connect) selected="selected" @endif value="1">{{ __('misc.enabled') }}</option>
                  <option @if (! $settings->stripe_connect) selected="selected" @endif value="0">{{ __('misc.disabled') }}</option>
                </select>
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-labe text-lg-end">{{ trans('misc.stripe_connect_countries') }}</label>
		          <div class="col-sm-10">
		            <select name="stripe_connect_countries[]" multiple class="form-select stripeConnectCountries">
									@foreach (Countries::orderBy('country_name')->get() as $country)
										<option @if (in_array($country->country_code, $stripeConnectCountries)) selected="selected" @endif value="{{$country->country_code}}">{{ $country->country_name }}</option>
									@endforeach
		           </select>
							 <small class="d-block">
								 {{ trans('misc.info_stripe_connect_countries') }} <a href="https://dashboard.stripe.com/settings/connect/express" target="_blank">https://dashboard.stripe.com/settings/connect/express</a>
							 </small>
		          </div>
		        </div>

						<div class="row mb-3">
		          <div class="col-sm-10 offset-sm-2">
		            <button type="submit" class="btn btn-dark mt-3 px-5">{{ __('admin.save') }}</button>
		          </div>
		        </div>

		       </form>

				 </div><!-- card-body -->
 			</div><!-- card  -->
 		</div><!-- col-lg-12 -->

	</div><!-- end row -->
</div><!-- end content -->
@endsection

@section('javascript')
  <script>
  $('.stripeConnectCountries').select2({
  tags: false,
  tokenSeparators: [','],
  placeholder: '{{ trans('misc.country') }}',
});
</script>
@endsection
