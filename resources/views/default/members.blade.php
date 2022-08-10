@extends('layouts.app')

@section('title'){{ trans('misc.members').' - ' }}@endsection

@section('content')
<section class="section section-sm">

<div class="container">
	<div class="row">

	<div class="col-lg-12 py-5">
		<h1 class="mb-0">
			{{ trans('misc.members') }} ({{number_format($users->total())}})
		</h1>
		<p class="lead text-muted mt-0">{{ trans('misc.members_desc') }}</p>
	  </div>

	@if ($users->count() != 0)
	<div class="col-md-12">

		<div class="btn-block mb-4">
			<span class="p-3 ps-0">
				<i class="bi bi-filter-right mr-2"></i> {{ trans('misc.sort') }}
			</span>

			<a class="text-muted btn btn-sm bg-white border me-2 e-none btn-category @if (url()->full() == url('members'))active-category @endif" href="{{url('members')}}">
				{{trans('misc.popular')}}
			</a>

		<a class="text-muted btn btn-sm bg-white border me-2 e-none btn-category @if (url()->full() == url('members?sort=latest'))active-category @endif" href="{{url('members?sort=latest')}}">
				{{trans('misc.latest')}}
			</a>

		<a class="text-muted btn btn-sm bg-white border me-2 e-none btn-category @if (url()->full() == url('members?sort=photos'))active-category @endif" href="{{url('members?sort=photos')}}">
				{{trans_choice('misc.photos_plural', 0)}}
			</a>

		</div><!-- btn-block -->
	</div><!-- col-md-12 -->
@endif

	@if (isset($settings->google_adsense))
		{!! $settings->google_adsense !!}
	@endif

<!-- Col MD -->
<div class="col-md-12" id="dataResult">

	<div class="row dataResult">
				@include('includes.users', ['users' => $users])
	</div>

        @if ($users->count() == 0)
	    		<h3 class="mt-0 fw-light">
	    		{{ trans('misc.no_results_found') }}
	    	</h3>
	    	@endif

 		</div><!-- /COL MD -->
	</div><!-- row -->
 </div><!-- container wrap-ui -->
</section>
@endsection
