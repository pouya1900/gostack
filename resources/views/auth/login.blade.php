@extends('layouts.app')

@section('title'){{ trans('auth.login').' - ' }}@endsection

@section('content')
  <div class="container-fluid">
        <div class="row">

          <div class="col-sm-6 px-0 d-none d-sm-block bg-auth"></div>

          <div class="col-sm-6 login-section-wrapper">
            <a href="{{ url('/') }}" class="mb-4 mb-lg-0 logo-login">
              <img src="{{ url('public/img', $settings->logo) }}" alt="logo" width="150">
            </a>
            <div class="login-wrapper my-auto">

              @include('errors.errors-forms')

          			@if (session('login_required'))
          			<div class="alert alert-danger">
              		<i class="fa fa-exclamation-triangle me-1"></i> {{ __('auth.login_required') }}
              		</div>
                @endif

                @if ($settings->facebook_login == 'on' || $settings->twitter_login == 'on' || $settings->google_login == 'on')
                <div class="d-flex mb-2">
                  @if ($settings->facebook_login == 'on')
            					<div class="w-100 d-block position-relative mb-2 me-2">
            						<a href="{{url('oauth/facebook')}}" class="btn btn-lg btn-facebook w-100">
                          <i class="fab fa-facebook me-1"></i> <span class="d-none d-lg-inline-block">Facebook</span>
                        </a>
            					</div>
            					@endif

                    @if ($settings->twitter_login == 'on')
              					<div class="w-100 d-block position-relative mb-2 me-2">
              						<a href="{{url('oauth/twitter')}}" class="btn btn-lg btn-twitter w-100">
                            <i class="fab fa-twitter me-1"></i> <span class="d-none d-lg-inline-block">Twitter</span>
                          </a>
              					</div>
              					@endif

                      @if ($settings->google_login == 'on')
                        <div class="w-100 d-block position-relative mb-2">
              						<a href="{{url('oauth/google')}}" class="btn btn-lg btn-google w-100">
                            <img src="{{ url('public/img/google.svg') }}" class="me-1" width="18" height="18" /> <span class="d-none d-lg-inline-block">Google</span>
                          </a>
              					</div>
                      @endif

                    </div><!-- d-flex -->

                    <small class="btn-block text-center my-3 text-uppercase or">{{ trans('misc.or') }}</small>
                  @endif

              <h3 class="login-title">{{ trans('auth.login') }}</h3>

              <form action="{{ url('login') }}" method="post" id="signup_form">

                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_url" value="{{ url()->previous() }}">

                @if($settings->captcha == 'on')
                  @captcha
                @endif

                <div class="form-floating mb-3">
                 <input type="text" required class="form-control" id="inputemail" value="{{old('email')}}" name="email" placeholder="{{ trans('auth.username_or_email') }}">
                 <label for="inputemail">{{ trans('auth.username_or_email') }}</label>
               </div>

               <div class="form-floating mb-3">
                <input type="password" required class="form-control showHideInput" id="inputepassword" name="password" placeholder="{{ trans('auth.password') }}">
                <label for="inputpassword">{{ trans('auth.password') }}</label>

                <span class="input-show-password" id="showHidePassword">
                  <i class="far fa-eye-slash"></i>
                </span>
              </div>

              <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="remember" value="1" id="flexCheckDefault" @if (old('remember')) checked="checked" @endif>
                <label class="form-check-label" for="flexCheckDefault">
                  {{ trans('auth.remember_me') }}
                </label>
              </div>

              <button type="submit" id="buttonSubmit" class="btn w-100 btn-lg btn-custom">{{ trans('auth.login') }}</button>

              @if ($settings->captcha == 'on')
                <small class="d-block mt-3">
                  {{trans('misc.protected_recaptcha')}}
                  <a href="https://policies.google.com/privacy" target="_blank">{{trans('misc.privacy')}}</a> - <a href="https://policies.google.com/terms" target="_blank">{{trans('misc.terms')}}</a>
                </small>
              @endif

              </form>

              <div class="my-2 d-block">
                <a href="{{url('password/reset')}}" class="text-reset">{{ trans('auth.forgot_password') }}</a>
              </div>

              @if ($settings->registration_active == 1)
              <p class="login-wrapper-footer-text">
                {{ trans('auth.not_have_account') }} <a href="{{ url('register') }}" class="text-reset text-decoration-underline">{{ trans('auth.sign_up') }}</a>
              </p>
            @endif

            </div>
          </div>

        </div>
      </div>

      @if (session('required_2fa'))
        @include('includes.modal-2fa')
      @endif

@endsection
