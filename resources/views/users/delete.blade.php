@extends('layouts.app')

@section('title') {{ trans('users.delete_account') }} - @endsection

@section('content')
<section class="section section-sm">
<div class="container">
  <div class="row justify-content-center">

		<!-- Col MD -->
		<div class="col-md-6">

      <div class="col-lg-12 py-5">
    		<h1 class="mb-0">
    			{{ trans('users.delete_account') }}
    		</h1>
    		<p class="lead text-muted mt-0">@lang('misc.alert_delete_account')</p>
    	  </div>

				<form action="{{ url('account/delete') }}" method="post" name="form">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<div class="d-block">
						<button type="submit" id="buttonSubmit" class="btn btn-lg btn-danger me-2">{{ trans('misc.yes_confirm') }}</button>
             		<a href="{{ url('account') }}" class="btn btn-light btn-lg border">{{ trans('misc.cancel_confirm') }}</a>
					</div>
				</form>

		</div><!-- /COL MD -->
	</div><!-- row -->
 </div><!-- container -->
</section>
@endsection
