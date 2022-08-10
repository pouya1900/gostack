@extends('layouts.app')

@section('title') {{ trans('misc.contact') }} - @endsection

@section('content')
<section class="section section-sm">

<div class="container">

  <div class="row justify-content-center">
	<!-- Col MD -->
	<div class="col-md-6">

    <div class="col-lg-12 py-5">
  		<h1 class="mb-0">
  			{{ trans('misc.contact') }}
  		</h1>
  		<p class="lead text-muted mt-0">@lang('misc.subtitle_contact')</p>
  	  </div>

			@if (session('notification'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('notification') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
          @endif

			@include('errors.errors-forms')

		<!-- ***** FORM ***** -->
       <form action="{{ url('contact') }}" method="post" name="form">

        <input type="hidden" name="_token" value="{{ csrf_token() }}">
            @captcha
        <div class="row">
          	<div class="col-md-6">
			    <!-- ***** Form Group ***** -->
           <div class="form-floating mb-3">
            <input type="text" required class="form-control" id="inputname" value="{{auth()->user()->username ??  old('full_name')}}" name="full_name" placeholder="{{ trans('users.name') }}" title="{{ trans('users.name') }}" autocomplete="off">
            <label for="inputname">{{ trans('users.name') }}</label>
          </div><!-- ***** Form Group ***** -->
          </div><!-- End Col MD-->

          <div class="col-md-6">
         <!-- ***** Form Group ***** -->
         <div class="form-floating mb-3">
          <input type="email" required class="form-control" id="inputemail" value="{{auth()->user()->email ??  old('email')}}" name="email" placeholder="{{ trans('auth.email') }}" title="{{ trans('auth.email') }}" autocomplete="off">
          <label for="inputemail">{{ trans('auth.email') }}</label>
        </div><!-- ***** Form Group ***** -->
          </div><!-- End Col MD-->
        </div><!-- End row -->

        <!-- ***** Form Group ***** -->
        <div class="form-floating mb-3">
         <input type="text" required class="form-control" id="inputsubject" value="{{old('subject')}}" name="subject" placeholder="{{ trans('misc.subject') }}" title="{{ trans('misc.subject') }}" autocomplete="off">
         <label for="inputsubject">{{ trans('misc.subject') }}</label>
       </div><!-- ***** Form Group ***** -->

       <!-- ***** Form Group ***** -->
       <div class="form-floating mb-3">
        <textarea class="form-control" name="message" required placeholder="{{ trans('misc.message') }}" id="floatingTextarea" style="height: 100px"></textarea>
        <label for="floatingTextarea">{{ trans('misc.message') }}</label>
      </div><!-- ***** Form Group ***** -->

      <button type="submit" class="btn w-100 btn-lg btn-custom">{{ trans('auth.send') }}</button>

      <small class="d-block text-center mt-3 text-muted">
        {{trans('misc.protected_recaptcha')}}
        <a href="https://policies.google.com/privacy" target="_blank">{{trans('misc.privacy')}}</a> - <a href="https://policies.google.com/terms" target="_blank">{{trans('misc.terms')}}</a>
      </small>

       </form><!-- ***** END FORM ***** -->

		</div><!-- /COL MD -->
    </div><!-- row -->
 </div><!-- container -->
</section>
@endsection
