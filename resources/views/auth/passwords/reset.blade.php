@extends('layouts.app')

@section('title'){{ trans('auth.reset_password').' - ' }}@endsection

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

              <h3 class="login-title">{{ trans('auth.reset_password') }}</h3>

              <form action="{{ url('/password/reset') }}" method="post">

                {{ csrf_field() }}
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-floating mb-3">
                 <input type="email" required class="form-control" id="inputemail" value="{{old('email')}}" name="email" placeholder="{{ trans('auth.email') }}"  autocomplete="off">
                 <label for="inputemail">{{ trans('auth.email') }}</label>
               </div>

               <div class="form-floating mb-3">
                <input type="password" required class="form-control showHideInput" id="inputepassword" name="password" placeholder="{{ trans('auth.password') }}">
                <label for="inputpassword">{{ trans('auth.password') }}</label>

                <span class="input-show-password" id="showHidePassword">
                  <i class="far fa-eye-slash"></i>
                </span>
              </div>

              <div class="form-floating mb-3">
               <input type="password" required class="form-control showHideInput" id="inputepassword2" name="password_confirmation" placeholder="{{ trans('auth.confirm_password') }}">
               <label for="inputepassword2">{{ trans('auth.confirm_password') }}</label>
             </div>

              <button type="submit" id="buttonSubmit" class="btn w-100 btn-lg btn-custom">{{ trans('auth.reset_password') }}</button>

              </form>

            </div>
          </div>

        </div>
      </div>
@endsection
