@extends('layouts.app')

@section('title'){{ $response->title.' - ' }}@endsection

@section('content')
<section class="section section-sm">

<div class="container">

<!-- Col MD -->
<div class="col-md-12">
  <div class="row">

    <div class="col-lg-12 py-5">
  		<h1 class="mb-0">
  			{{ $response->title }}
  		</h1>
  	  </div>

     <dl>
     	<dd>
     		{!! $response->content !!}
     	</dd>
     </dl>

  </div><!-- row -->
 </div><!-- container wrap-ui -->
</section>
@endsection
