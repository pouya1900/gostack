<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="{{ asset('public/js/core.min.js') }}?v={{$settings->version}}"></script>
<script src="{{ asset('public/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('public/js/fleximages/jquery.flex-images.min.js') }}"></script>
<script src="{{ asset('public/js/timeago/jqueryTimeago_'.Lang::locale().'.js') }}"></script>
<script src="{{ asset('public/js/functions.js') }}?v={{$settings->version}}"></script>
<script src="{{ asset('public/js/install-app.js') }}?v={{$settings->version}}"></script>
<script src="{{ asset('public/js/video-preview.js') }}?v={{$settings->version}}"></script>

<script type="text/javascript">

@if ($settings->custom_js)
  {!! $settings->custom_js !!}
@endif

@if (session('required_2fa'))
var myModal = new bootstrap.Modal(document.getElementById('modal2fa'), {
  backdrop: 'static',
  keyboard: false
});
myModal.show();
@endif
</script>
