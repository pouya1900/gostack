<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- Favicons
================================================== -->
<link rel="shortcut icon" href="{{url('public/img', $settings->favicon)}}" />
<title>{{ __('misc.maintenance_mode') }}</title>

<!-- CSS
================================================== -->
<link href="{{ asset('public/css/core.min.css') }}" rel="stylesheet">
<link href="{{ asset('public/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('public/css/styles.css') }}" rel="stylesheet">
</head>
<body>
  <div class="section full-height over-hide">
  		<div class="hero-center-wrap z-bigger">
  			<div class="container">
  				<div class="row">
  					<div class="col-md-12 error-page text-center parallax-fade-top" style="top: 0px; opacity: 1;">
  						<h1 class="vivify popIn delay-1000 mb-lg-4">{{ __('misc.sorry') }}</h1>
  						<p class="mt-3 mb-5 vivify popIn delay-1000">{{ __('misc.msg_maintenance_mode') }}</p>
  					</div>
  				</div>
  			</div>
  		</div>
  	</div>
</body>
</html>
