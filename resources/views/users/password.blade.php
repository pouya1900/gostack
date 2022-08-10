@extends('layouts.app')

@section('title') {{ trans('auth.password') }} - @endsection

@section('content')
<section class="section section-sm">

<div class="container-custom container py-5">
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

            	 @if (session('incorrect_pass'))
			<div class="alert alert-danger alert-dismissible fade show" role="alert">
            		{{ session('incorrect_pass') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                  <i class="bi bi-x-lg"></i>
                </button>
            		</div>
            	@endif

			@include('errors.errors-forms')

      <h5 class="mb-4">{{ trans('auth.password') }}</h5>

		<!-- ***** FORM ***** -->
       <form action="{{ url('account/password') }}" method="post" name="form">

          	<input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="form-floating mb-3">
             <input type="password" required class="form-control" id="input-oldpassword" name="old_password" placeholder="{{ trans('misc.old_password') }}">
             <label for="input-oldpassword">{{ trans('misc.old_password') }}</label>
           </div>

           <div class="form-floating mb-3">
            <input type="password" required class="form-control" id="input-password" name="password" placeholder="{{ trans('misc.new_password') }}">
            <label for="input-password">{{ trans('misc.new_password') }}</label>
          </div>

           <button type="submit" id="buttonSubmit" class="btn w-100 btn-lg btn-custom">{{ trans('misc.save_changes') }}</button>
       </form><!-- ***** END FORM ***** -->


		</div><!-- /COL MD -->
  </div><!-- row -->
 </div><!-- container -->
</section>
 <!-- container wrap-ui -->
@endsection
