<!-- Bootstrap core CSS -->
<link href="{{ asset('public/css/core.min.css') }}?v={{$settings->version}}" rel="stylesheet">
<link href="{{ asset('public/css/bootstrap.min.css') }}?v={{$settings->version}}" rel="stylesheet">
<link href="{{ asset('public/css/bootstrap-icons.css') }}" rel="stylesheet">
<link href="{{ asset('public/js/fleximages/jquery.flex-images.css') }}" rel="stylesheet">
<link href="{{ asset('public/css/styles.css') }}?v={{$settings->version}}" rel="stylesheet">

<script type="text/javascript">
    var URL_BASE = "{{ url('/') }}";
    var lang = '{{ session('locale') }}';
    var _title = '@section("title")@show {{e($settings->title.' - '.__('seo.welcome_subtitle'))}}';
    var session_status = "{{ auth()->check() ? 'on' : 'off' }}";
    var colorStripe = '#000000';
    var copiedSuccess = "{{ trans('misc.copied_success') }}";
    var error = "{{trans('misc.error')}}";
    var error_oops = "{{trans('misc.error_oops')}}";
    var resending_code = "{{trans('misc.resending_code')}}";
    var isProfile = {{ request()->route()->named('profile') ? 'true' : 'false' }};
    var download = '{{trans('misc.download')}}';
    var downloading = '{{trans('misc.downloading')}}';
    
    @auth
      var stripeKey = "{{ PaymentGateways::where('id', 2)->where('enabled', '1')->first() ? env('STRIPE_KEY') : false }}";
      var delete_confirm = "{{trans('misc.delete_confirm')}}";
      var confirm_delete = "{{ __('misc.yes') }}";
      var cancel_confirm = "{{ __('misc.no') }}";
      var your_subscribed = "{{trans('misc.your_subscribed')}}";
      var announcement_cookie = "{{$settings->announcement_cookie}}";
    @endauth
 </script>

<style type="text/css">

@if ($settings->custom_css)
  {!! $settings->custom_css !!}
@endif

 .home-cover { background-image: url('{{ url('public/img', $settings->image_header) }}') }
 :root {
   --color-default: {{ $settings->color_default }} !important;
   --bg-auth: url('{{ url('public/img', $settings->image_header) }}');
 }
 </style>
