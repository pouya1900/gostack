@extends('layouts.app')

@section('title') {{ trans('users.account_settings') }} - @endsection

@section('content')
<section class="section section-sm">

<div class="container-custom container pt-5">
<div class="row">

  <div class="col-md-3">
    @include('users.navbar-settings')
  </div>

			<!-- Col MD -->
		<div class="col-md-9">

			@if (session('notification'))
			<div class="alert alert-success alert-dismissible fade show" role="alert">
            	<i class="bi bi-check2 me-1"></i>	{{ session('notification') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                  <i class="bi bi-x-lg"></i>
                </button>
            		</div>
            	@endif

			@include('errors.errors-forms')

      <h5 class="mb-4">{{ trans('users.account_settings') }}</h5>

		<!-- ***** FORM ***** -->
       <form action="{{ url('account') }}" method="post">

        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="row">
        	<div class="col-md-6">
            <div class="form-floating mb-3">
             <input type="text" required class="form-control" id="inputname" value="{{auth()->user()->name}}" name="full_name" placeholder="{{ trans('misc.full_name_misc') }}">
             <label for="inputname">{{ trans('misc.full_name_misc') }}</label>
           </div>
           </div><!-- End Col MD-->

            <div class="col-md-6">
              <div class="form-floating mb-3">
               <input type="email" required class="form-control" id="inputemail" value="{{auth()->user()->email}}" name="email" placeholder="{{ trans('auth.email') }}">
               <label for="inputemail">{{ trans('auth.email') }}</label>
             </div>
            </div><!-- End Col MD-->

        </div><!-- End row -->

			<div class="row">

				<div class="col-md-6">
          <div class="form-floating mb-3">
           <input type="text" required class="form-control" id="inputusername" value="{{auth()->user()->username}}" name="username" placeholder="{{ trans('misc.username_misc') }}">
           <label for="inputusername">{{ trans('misc.username_misc') }}</label>
         </div>
				</div><!-- End Col MD-->

				<div class="col-md-6">

          <div class="form-floating mb-3">
          <select name="countries_id" class="form-select" id="inputSelectCountry">
            <option value="">{{trans('misc.select_your_country')}}</option>

            @foreach (Countries::orderBy('country_name')->get() as $country)
              <option @if( auth()->user()->countries_id == $country->id ) selected="selected" @endif value="{{$country->id}}">{{ $country->country_name }}</option>
              @endforeach
          </select>
          <label for="inputSelectCountry">{{ trans('misc.country') }}</label>
        </div>

				</div><!-- End Col MD-->
			</div><!-- End row -->

      @if ($settings->who_can_sell == 'all'
          || $settings->who_can_sell == 'admin'
          && auth()->user()->isSuperAdmin())

      <div class="form-floating">
      <select name="author_exclusive" class="form-select" id="authorExclusive">
        <option @if (auth()->user()->author_exclusive == 'yes') selected="selected" @endif value="yes">{{trans('misc.exclusive_author')}}</option>
        <option @if (auth()->user()->author_exclusive == 'no') selected="selected" @endif value="no">{{trans('misc.non_exclusive_author')}}</option>
      </select>
      <small class="d-block pb-3 fw-bold mt-1">

        <span id="percentage">
          @if (auth()->user()->author_exclusive == 'yes')
            * {{ trans('misc.user_gain', ['percentage' => (100 - $settings->fee_commission)]) }}
          @else
            * {{ trans('misc.user_gain', ['percentage' => (100 - $settings->fee_commission_non_exclusive)]) }}
            @endif
        </span>

        <i class="bi bi-info-circle showTooltip ms-1" title="{{trans('misc.earnings_information')}}"></i>
      </small>
      <label for="authorExclusive">{{ trans('misc.exclusivity_items') }}</label>
    </div>
  @endif

      <div class="form-floating mb-3">
       <input type="email" class="form-control" id="inputpaypal_account" value="{{auth()->user()->paypal_account}}" name="paypal_account" placeholder="{{ trans('admin.paypal_account') }}">
       <label for="inputpaypal_account">{{ trans('admin.paypal_account') }}</label>
     </div>

       <div class="form-floating mb-3">
        <input type="url" class="form-control" id="input-website_misc" value="{{auth()->user()->website}}" name="website" placeholder="{{ trans('misc.website_misc') }}">
        <label for="input-website_misc">{{ trans('misc.website_misc') }}</label>
      </div>

      <div class="form-floating mb-3">
       <input type="url" class="form-control" id="input-facebook" value="{{auth()->user()->facebook}}" name="facebook" placeholder="Facebook">
       <label for="input-facebook">Facebook (URL)</label>
     </div>

       <div class="form-floating mb-3">
        <input type="url" class="form-control" id="input-twitter" value="{{auth()->user()->twitter}}" name="twitter" placeholder="Twitter">
        <label for="input-twitter">Twitter (URL)</label>
      </div>

      <div class="form-floating mb-3">
       <input type="url" class="form-control" id="input-instagram" value="{{auth()->user()->instagram}}" name="instagram" placeholder="Instagram">
       <label for="input-instagram">Instagram (URL)</label>
     </div>

     <div class="form-floating mb-3">
      <textarea class="form-control" placeholder="{{ trans('misc.description') }}" name="description" id="input-description" style="height: 100px">{{ auth()->user()->bio }}</textarea>
      <label for="input-description">{{ trans('misc.description') }}</label>
    </div>

    <div class="form-check form-switch form-switch-md mb-3 options_free @if ($settings->free_photo_upload == 'off') display-none @endif">
      <input class="form-check-input" @if (auth()->user()->two_factor_auth == 'yes') checked @endif name="two_factor_auth" type="checkbox" value="yes" id="flexSwitchCheckDefault">
      <label class="form-check-label" for="flexSwitchCheckDefault">{{ trans('misc.two_step_auth') }} <i class="bi bi-info-circle ms-1 text-muted showTooltip" title="{{ trans('misc.two_step_auth_info') }}"></i></label>
    </div>

           <button type="submit" id="buttonSubmit" class="btn w-100 btn-lg btn-custom">{{ trans('misc.save_changes') }}</button>

         @if (auth()->id() != 1)
           <div class="d-block text-center mt-3">
           		<a href="{{url('account/delete')}}" class="text-danger">{{trans('users.delete_account')}}</a>
           </div>
           @endif
       </form><!-- ***** END FORM ***** -->

  </div><!-- /COL MD -->
  </div><!-- row -->
</div><!-- container -->
</section>
@endsection

@section('javascript')
<script type="text/javascript">

$('#authorExclusive').on('change', function() {
  if ($(this).val() == 'yes') {
    $('#percentage').html('* {{ trans('misc.user_gain', ['percentage' => (100 - $settings->fee_commission)]) }}');

  } else {
      $('#percentage').html('* {{ trans('misc.user_gain', ['percentage' => (100 - $settings->fee_commission_non_exclusive)]) }}');
  }
});

</script>
@endsection
