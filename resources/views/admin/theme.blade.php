@extends('admin.layout')

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ __('admin.theme') }}</span>
  </h5>

<div class="content">
	<div class="row">

		<div class="col-lg-12">

			@if (session('success_message'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
              <i class="bi bi-check2 me-1"></i>	{{ session('success_message') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                  <i class="bi bi-x-lg"></i>
                </button>
                </div>
              @endif

              @include('errors.errors-forms')

			<div class="card shadow-custom border-0">
				<div class="card-body p-lg-5">

					 <form method="post" action="{{{ url('panel/admin/theme') }}}" enctype="multipart/form-data">
             @csrf

		        <div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">Logo dark</label>
		          <div class="col-lg-5 col-sm-10">
                <div class="d-block mb-2">
                  <img src="{{url('/public/img', $settings->logo)}}" style="width:150px">
                </div>

                <div class="input-group mb-1">
                  <input name="logo" type="file" class="form-control custom-file rounded-pill">
                </div>
                <small class="d-block">{{ __('misc.recommended_size') }} 400x400 px (PNG)</small>
		          </div>
		        </div>

            <div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">Logo light</label>
		          <div class="col-lg-5 col-sm-10">
                <div class="d-block mb-2">
                  <img src="{{url('/public/img', $settings->logo_light)}}" class="bg-secondary" style="width:150px">
                </div>

                <div class="input-group mb-1">
                  <input name="logo_light" type="file" class="form-control custom-file rounded-pill">
                </div>
                <small class="d-block">{{ __('misc.recommended_size') }} 400x400 px (PNG)</small>
		          </div>
		        </div>

            <div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">Favicon</label>
		          <div class="col-lg-5 col-sm-10">
                <div class="d-block mb-2">
                  <img src="{{url('/public/img', $settings->favicon)}}">
                </div>

                <div class="input-group mb-1">
                  <input name="favicon" type="file" class="form-control custom-file rounded-pill">
                </div>
                <small class="d-block">{{ __('misc.recommended_size') }} 48x48 px (PNG)</small>
		          </div>
		        </div>

            <div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">{{ __('misc.index_image_top') }}</label>
		          <div class="col-lg-5 col-sm-10">
                <div class="d-block mb-2">
                  <img src="{{url('/public/img', $settings->image_header)}}" style="width:200px">
                </div>

                <div class="input-group mb-1">
                  <input name="image_header" type="file" class="form-control custom-file rounded-pill">
                </div>
                <small class="d-block">{{ __('misc.recommended_size') }} 1280x850 px (JPG)</small>
		          </div>
		        </div>

            <div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">Image index section</label>
		          <div class="col-lg-5 col-sm-10">
                <div class="d-block mb-2">
                  <img src="{{url('/public/img', $settings->img_section)}}" style="width:200px">
                </div>

                <div class="input-group mb-1">
                  <input name="img_section" type="file" class="form-control custom-file rounded-pill">
                </div>
                <small class="d-block">{{ __('misc.recommended_size') }} 800x600 px (JPG, PNG)</small>
		          </div>
		        </div>

            <div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">Watermark</label>
		          <div class="col-lg-5 col-sm-10">
                <div class="d-block mb-2">
                  <img src="{{url('/public/img', $settings->watermark)}}" class="bg-secondary" style="width:300px">
                </div>

                <div class="input-group mb-1">
                  <input name="watermark" type="file" class="form-control custom-file rounded-pill">
                </div>
                <small class="d-block">{{ __('misc.recommended_size') }} 700px (PNG)</small>
								<small class="d-block fst-italic">* {{ __('misc.clean_cache_browser') }}</small>
		          </div>
		        </div>

            <div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">Avatar default</label>
		          <div class="col-lg-5 col-sm-10">
                <div class="d-block mb-2">
                  <img src="{{Storage::url(config('path.avatar').$settings->avatar)}}" style="width:180px">
                </div>

                <div class="input-group mb-1">
                  <input name="avatar" type="file" class="form-control custom-file rounded-pill">
                </div>
                <small class="d-block">{{ __('misc.recommended_size') }} 250x250 px (JPG)</small>
		          </div>
		        </div>

            <div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">Cover User</label>
		          <div class="col-lg-5 col-sm-10">
                <div class="d-block mb-2">
                  <img src="{{Storage::url(config('path.cover').$settings->cover)}}" style="width:200px">
                </div>

                <div class="input-group mb-1">
                  <input name="cover" type="file" class="form-control custom-file rounded-pill">
                </div>
                <small class="d-block">{{ __('misc.recommended_size') }} 1280x850 px (JPG)</small>
		          </div>
		        </div>

            <div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">Image default in categories</label>
		          <div class="col-lg-5 col-sm-10">
                <div class="d-block mb-2">
                  <img src="{{url('public/img-category', $settings->img_category)}}" style="width:250px">
                </div>

                <div class="input-group mb-1">
                  <input name="img_category" type="file" class="form-control custom-file rounded-pill">
                </div>
                <small class="d-block">{{ __('misc.recommended_size') }} 457x357 px (JPG)</small>
		          </div>
		        </div>

            <div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">{{ __('misc.color_default') }}</label>
		          <div class="col-sm-10">
                <input type="color" name="color_default" class="form-control form-control-color" value="{{ $settings->color_default }}">
		          </div>
		        </div>

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
