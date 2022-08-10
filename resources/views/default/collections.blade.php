@extends('layouts.app')

@section('title'){{ $title }}@endsection

@section('content')
<section class="section section-sm">

<div class="container">
  <div class="row">

    <div class="col-lg-12 py-5">
  		<h1 class="mb-0">
  			{{ trans('misc.collections') }} ({{number_format($data->total())}})
  		</h1>
  		<p class="lead text-muted mt-0">{{ __('misc.collections_desc') }}</p>
  	  </div>

    @if ($data->total() != 0)
      <div class="container">
        <div class="row dataResult">
          @include('includes.collections-grid')
        </div>
     </div>

	  @else
      <!-- Col MD -->
      <div class="col-md-12">
	    	<h3 class="mt-0 fw-light">
	    		{{ trans('misc.no_results_found') }}
	    	</h3>
      </div><!-- /COL MD -->
	  @endif

  </div><!-- row -->
 </div><!-- container wrap-ui -->
</section>
@endsection

@section('javascript')

<script type="text/javascript">

 $('#imagesFlex').flexImages({ rowHeight: 220 });

</script>
@endsection
