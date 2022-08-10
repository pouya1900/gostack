@extends('admin.layout')

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
			<a class="text-reset" href="{{ url('panel/admin/roles-and-permissions') }}">{{ __('admin.role_and_permissions') }}</a>
			<i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ __('misc.add_new') }}</span>
  </h5>

<div class="content">
	<div class="row">

		<div class="col-lg-12">

			@if (session('error_message'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <i class="bi bi-check2 me-1"></i>	{{ session('error_message') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                  <i class="bi bi-x-lg"></i>
                </button>
                </div>
              @endif

			@include('errors.errors-forms')

			<div class="card shadow-custom border-0">
				<div class="card-body p-lg-5">

					 <form method="POST" action="{{ url('panel/admin/roles-and-permissions/create') }}" enctype="multipart/form-data">
						 @csrf

		        <div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">{{ __('admin.name') }}</label>
		          <div class="col-sm-10">
		            <input type="text" name="name" required value="{{ old('name') }}" class="form-control">
		          </div>
		        </div>

					@include('admin.permissions')

						<div class="row mb-3">
		          <div class="col-sm-10 offset-sm-2">
		            <button type="submit" class="btn btn-dark mt-3 px-5">{{ __('admin.save') }}</button>
		          </div>
		        </div>

		       </form>

				 </div><!-- card-body -->
 			</div><!-- card  -->
 		</div><!-- col-lg-12 -->

	</div><!-- end row -->
</div><!-- end content -->
@endsection

@section('javascript')

<script>

var triggeredByChild = false;

$('.limitedAccess').on('change', function (event) {

	if ($(this).is(":checked")) {
		triggeredByChild = false;
    $('.check').prop('checked', false);
    $('#select-all').prop('checked', false);
	}

});

$('#select-all').on('change', function (event) {

	if ($(this).is(":checked")) {
    $('.check').prop('checked', true);
    $('.limitedAccess').prop('checked', false);
    triggeredByChild = false;
	}
});

$('.check').on('change', function (event) {
	if ($(this).is(":checked")) {
    triggeredByChild = false;
    $('.limitedAccess').prop('checked', false);
	}
});

$('#select-all').on('change', function (event) {

	if (! $(this).is(":checked")) {
    if (! triggeredByChild) {
        $('.check').prop('checked', false);
    }
    triggeredByChild = false;
	}
});
// Removed the checked state from "All" if any checkbox is unchecked
$('.check').on('change', function (event) {
	if (! $(this).is(":checked")) {
    triggeredByChild = true;
    $('#select-all').prop('checked', false);
	}
});
</script>
@endsection
