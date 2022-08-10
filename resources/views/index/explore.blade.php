@extends('layouts.app')

@php
switch(request()->get('timeframe')) {

	case 'today':
		$timeframe_text = ' '.trans('misc.today');
		break;
	case 'week':
				$timeframe_text = ' '.trans('misc.this_week');
		break;
	case 'month':
				$timeframe_text = ' '.trans('misc.this_month');
		break;
	case 'year':
				$timeframe_text = ' '.trans('misc.this_year');
		break;
	default:
				$timeframe_text = null;
	}
@endphp

@section('title'){{ $title.$timeframe_text.' - ' }}@endsection

@section('content')

<section class="section section-sm">

<div class="container">
	<div class="row">

		<div class="col-lg-12 py-5">
			<h1 class="mb-0">
				{{ $title }}
			</h1>
			<p class="lead text-muted mt-0">{{ $description }}</p>
		  </div>

	<!-- col-md-12 -->
	<div class="col-md-12">

		@if ($images->total() != 0)

		@if (request()->is('featured')
		 || request()->is('popular')
		 || request()->is('most/commented')
		 || request()->is('most/viewed')
		 || request()->is('most/downloads')
		 )
	<div class="d-block w-100 mb-3 text-end">

		<select class="ms-2 form-select d-inline-block w-auto me-2 filter filter-explore">
			<option @if (request()->is('featured')) selected @endif value="{{ url('featured') }}">{{trans('misc.featured')}}</option>
			<option @if (request()->is('popular')) selected @endif value="{{ url('popular') }}">{{trans('misc.popular')}}</option>
			@if ($settings->comments)
			<option @if (request()->is('most/commented')) selected @endif value="{{ url('most/commented') }}">{{trans('misc.most_commented')}}</option>
			@endif
			<option @if (request()->is('most/viewed')) selected @endif value="{{ url('most/viewed') }}">{{trans('misc.most_viewed')}}</option>
			<option @if (request()->is('most/downloads')) selected @endif value="{{ url('most/downloads') }}">{{trans('misc.most_downloads')}}</option>
		</select>

		<select class="ms-2 form-select d-inline-block w-auto filter filter-explore">
			<option @if (! request()->get('timeframe')) selected @endif value="{{ url()->current() }}">{{trans('misc.all_time')}}</option>
			<option @if (request()->get('timeframe') == 'today') selected @endif value="{{ url()->current() }}?timeframe=today">{{trans('misc.today')}}</option>
			<option @if (request()->get('timeframe') == 'week') selected @endif value="{{ url()->current() }}?timeframe=week">{{trans('misc.this_week')}}</option>
			<option @if (request()->get('timeframe') == 'month') selected @endif value="{{ url()->current() }}?timeframe=month">{{trans('misc.this_month')}}</option>
			<option @if (request()->get('timeframe') == 'year') selected @endif value="{{ url()->current() }}?timeframe=year">{{trans('misc.this_year')}}</option>
			</select>
		</div>
		  @endif

		<div class="dataResult">
	     @include('includes.images')
		 	@include('includes.pagination-links')
		 </div>

	  @else
		<h3 class="mt-0 fw-light">
			{{ trans('misc.no_results_found') }}
		</h3>
	  @endif

		</div><!-- col-md-12-->

	</div><!-- row -->
</div><!-- container -->
</section>
@endsection

@section('javascript')

<script type="text/javascript">
$('#imagesFlex').flexImages({ rowHeight: 320 });
</script>
@endsection
