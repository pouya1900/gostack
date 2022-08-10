@extends('layouts.app')

@section('title'){{ $title }}@endsection

@section('content')
<section class="section section-sm">

 <div class="container">
   <div class="row">

  <div class="col-lg-12 py-5">
    <h1 class="mb-0 text-break">
      {{ trans('misc.feed') }}
    </h1>
    <p class="lead text-muted mt-0">{{ trans('misc.following_feed') }}</p>
    </div>

<!-- Col MD -->
<div class="col-md-12">

	@if ($images->total() != 0)

    <div class="dataResult">
       @include('includes.images')
       @include('includes.pagination-links')
     </div>

	  @else
	    		<h3 class="mt-0 fw-light">
	    		{{ trans('misc.no_results_found') }}
	    	</h3>
	  @endif

 </div><!-- /COL MD -->
	</div><!-- row -->
 </div><!-- container wrap-ui -->
</section>
@endsection

@section('javascript')

<script type="text/javascript">

 $('#imagesFlex').flexImages({ rowHeight: 320 });

</script>
@endsection
