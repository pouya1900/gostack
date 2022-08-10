@extends('layouts.app')

@section('title'){{ trans('auth.sign_up').' - ' }}@endsection

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

              @if (session('status'))
      						<div class="alert alert-success text-center mt-3">
      							<i class="bi bi-stars me-2"></i> {{ session('status') }}
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

              <h3 class="login-title">{{ trans('auth.sign_up') }}</h3>

              <form action="{{ url('register') }}" method="post" id="signup_form">

                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                @if($settings->captcha == 'on')
                  @captcha
                @endif

                <div class="row">

                  <div class="col-md-6">
                   <div class="form-floating mb-3">
                    <input type="text" required class="form-control" id="inputname" value="{{ old('username') }}" name="username" placeholder="{{ trans('auth.username') }}" autocomplete="off">
                    <label for="inputname">{{ trans('auth.username') }}</label>
                  </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-floating mb-3">
                     <input type="email" required class="form-control" id="inputemail" value="{{old('email')}}" name="email" placeholder="{{ trans('auth.email') }}" autocomplete="off">
                     <label for="inputemail">{{ trans('auth.email') }}</label>
                   </div>
                  </div>

               </div><!-- row -->

               <div class="form-floating mb-3">
                <input type="password" minlength="8" required class="form-control showHideInput" id="inputepassword" name="password" placeholder="{{ trans('auth.password') }}">
                <label for="inputpassword">{{ trans('auth.password') }}</label>

                <span class="input-show-password" id="showHidePassword">
                  <i class="far fa-eye-slash"></i>
                </span>
              </div>

              <div class="form-floating mb-3">
               <input type="password" minlength="8" required class="form-control showHideInput" id="inputepassword2" name="password_confirmation" placeholder="{{ trans('auth.confirm_password') }}">
               <label for="inputepassword2">{{ trans('auth.confirm_password') }}</label>
             </div>

              <div class="form-check mb-3">
                <input class="form-check-input" required type="checkbox" name="agree_gdpr" value="1" id="flexCheckDefault" @if (old('agree_gdpr')) checked="checked" @endif>
                <label class="form-check-label" for="flexCheckDefault">
                  {{ trans('admin.i_agree_gdpr') }}

                  @if ($settings->link_privacy != '')
                    <a href="{{$settings->link_privacy}}" target="_blank">{{ trans('admin.privacy_policy') }}</a>
                  @endif

                </label>
              </div>

              <button type="submit" id="buttonSubmitRegister" class="btn w-100 btn-lg btn-custom">{{ trans('auth.sign_up') }}</button>

              @if ($settings->captcha == 'on')
                <small class="d-block mt-3">
                  {{trans('misc.protected_recaptcha')}}
                  <a href="https://policies.google.com/privacy" target="_blank">{{trans('misc.privacy')}}</a> - <a href="https://policies.google.com/terms" target="_blank">{{trans('misc.terms')}}</a>
                </small>
              @endif

              </form>

              @if ($settings->registration_active == 1)
              <p class="login-wrapper-footer-text mt-3">
                {{ trans('auth.already_have_an_account') }} <a href="{{ url('login') }}" class="text-reset text-decoration-underline">{{ trans('auth.login') }}</a>
              </p>
            @endif

            </div>
          </div>

        </div>
      </div>
@endsection
