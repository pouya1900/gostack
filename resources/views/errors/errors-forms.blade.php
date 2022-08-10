@if (count($errors) > 0)
	<!-- Start Box Body -->
  <div class="box-body">
	<div class="alert alert-danger alert-dismissible" id="dangerAlert">

		{{trans('auth.error_desc')}} <br><br>
		<ul class="list-unstyled m-0">
			@foreach ($errors->all() as $error)
				<li><i class="far fa-times-circle"></i> {{$error}}</li>
			@endforeach
		</ul>

    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
      <i class="bi bi-x-lg"></i>
    </button>
	</div>
</div><!-- /.box-body -->
@endif
