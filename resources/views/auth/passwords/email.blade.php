@extends('layouts.app')

@section('title'){{ trans('auth.password_recover').' - ' }}@endsection

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
          						<div class="alert alert-success">
          							<i class="bi bi-check2 me-1"></i> {{ session('status') }}
          						</div>
          					@endif

              <h3 class="login-title">{{ trans('auth.password_recover') }}</h3>

              <form action="{{ url('/password/email') }}" method="post">

                {{ csrf_field() }}

                <div class="form-floating mb-3">
                 <input type="email" required class="form-control" id="inputemail" value="{{old('email')}}" name="email" placeholder="{{ trans('auth.email') }}"  autocomplete="off">
                 <label for="inputemail">{{ trans('auth.email') }}</label>
               </div>

              <button type="submit" id="buttonSubmit" class="btn w-100 btn-lg btn-custom">{{ trans('auth.send') }}</button>

              <a href="{{{ url('login') }}}" class="text-center d-block mt-3 text-reset">
                <i class="bi bi-arrow-left"></i> {{{ trans('auth.back') }}}
              </a>

              </form>

            </div>
          </div>

        </div>
      </div>
@endsection
