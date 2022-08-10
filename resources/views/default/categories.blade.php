@extends('layouts.app')

@section('title'){{ trans('misc.categories').' - ' }}@endsection

@section('content')
<section class="section section-sm">
  <div class="container">
    <div class="row">

    <div class="col-lg-12 py-5">
  		<h1 class="mb-0">
  			{{ trans('misc.categories') }}
  		</h1>
  		<p class="lead text-muted mt-0">{{ trans('misc.browse_by_category') }}</p>
  	  </div>

    @include('includes.categories-listing')

    </div><!-- row -->
 </div><!-- container wrap-ui -->
</section>
@endsection
