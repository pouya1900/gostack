@extends('admin.layout')

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ __('admin.storage') }}</span>
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

  @if (count($errors) > 0)
	<div class="alert alert-danger alert-dismissible fade show" role="alert">
        	{{ __('auth.error_desc') }}

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
              <i class="bi bi-x-lg"></i>
            </button>
        		</div>
					@endif

			<div class="card shadow-custom border-0">
				<div class="card-body p-lg-5">

					 <form method="POST" action="{{ url('panel/admin/storage') }}" enctype="multipart/form-data">
						 @csrf

		        <div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">App URL</label>
		          <div class="col-sm-10">
		            <input  value="{{ env('APP_URL') }}" name="APP_URL" type="text" class="form-control @error('APP_URL') is-invalid @endif">
								<small class="d-block mt-1">{{__('misc.notice_app_url')}} <strong>{{url('/')}}</strong></small>
		          </div>
		        </div>

		        <div class="row mb-5">
		          <label class="col-sm-2 col-form-labe text-lg-end">{{__('misc.disk')}}</label>
		          <div class="col-sm-10">
		            <select name="FILESYSTEM_DRIVER" class="form-select">
									 <option @if (env('FILESYSTEM_DRIVER') == 'default') selected @endif value="default">{{__('misc.disk_local')}}</option>
	 								 <option @if (env('FILESYSTEM_DRIVER') == 's3') selected @endif value="s3">Amazon S3</option>
	 								 <option @if (env('FILESYSTEM_DRIVER') == 'dospace') selected @endif value="dospace">DigitalOcean</option>
	 								 <option @if (env('FILESYSTEM_DRIVER') == 'wasabi') selected @endif value="wasabi">Wasabi</option>
									 <option @if (env('FILESYSTEM_DRIVER') == 'vultr') selected @endif value="vultr">Vultr</option>
		           </select>
		          </div>
		        </div>

						<hr />

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">Amazon Key</label>
		          <div class="col-sm-10">
		            <input value="{{ env('AWS_ACCESS_KEY_ID') }}" name="AWS_ACCESS_KEY_ID" type="text" class="form-control @error('AWS_ACCESS_KEY_ID') is-invalid @endif">
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">Amazon Secret</label>
		          <div class="col-sm-10">
		            <input value="{{ env('AWS_SECRET_ACCESS_KEY') }}" name="AWS_SECRET_ACCESS_KEY" type="text" class="form-control @error('AWS_SECRET_ACCESS_KEY') is-invalid @endif">
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">Amazon Region</label>
		          <div class="col-sm-10">
		            <input value="{{ env('AWS_DEFAULT_REGION') }}" name="AWS_DEFAULT_REGION" type="text" class="form-control @error('AWS_DEFAULT_REGION') is-invalid @endif">
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">Amazon Bucket</label>
		          <div class="col-sm-10">
		            <input value="{{ env('AWS_BUCKET') }}" name="AWS_BUCKET" type="text" class="form-control @error('AWS_BUCKET') is-invalid @endif">
		          </div>
		        </div>

						<hr />

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">DigitalOcean Key</label>
		          <div class="col-sm-10">
		            <input value="{{ env('DOS_ACCESS_KEY_ID') }}" name="DOS_ACCESS_KEY_ID" type="text" class="form-control @error('DOS_ACCESS_KEY_ID') is-invalid @endif">
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">DigitalOcean Secret</label>
		          <div class="col-sm-10">
		            <input value="{{ env('DOS_SECRET_ACCESS_KEY') }}" name="DOS_SECRET_ACCESS_KEY" type="text" class="form-control @error('DOS_SECRET_ACCESS_KEY') is-invalid @endif">
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">DigitalOcean Region</label>
		          <div class="col-sm-10">
		            <input value="{{ env('DOS_DEFAULT_REGION') }}" name="DOS_DEFAULT_REGION" type="text" class="form-control @error('DOS_DEFAULT_REGION') is-invalid @endif">
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">DigitalOcean Bucket</label>
		          <div class="col-sm-10">
		            <input value="{{ env('DOS_BUCKET') }}" name="DOS_BUCKET" type="text" class="form-control @error('DOS_BUCKET') is-invalid @endif">
		          </div>
		        </div>

						<hr />

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">Wasabi Key</label>
		          <div class="col-sm-10">
		            <input value="{{ env('WAS_ACCESS_KEY_ID') }}" name="WAS_ACCESS_KEY_ID" type="text" class="form-control @error('WAS_ACCESS_KEY_ID') is-invalid @endif">
								<small class="d-block mt-1"><strong>Important:</strong> Wasabi in trial mode does not allow public files, you must send an email to <strong>support@wasabi.com</strong> to enable public files, or avoid trial mode.</small>
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">Wasabi Secret</label>
		          <div class="col-sm-10">
		            <input value="{{ env('WAS_SECRET_ACCESS_KEY') }}" name="WAS_SECRET_ACCESS_KEY" type="text" class="form-control @error('WAS_SECRET_ACCESS_KEY') is-invalid @endif">
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">Wasabi Region</label>
		          <div class="col-sm-10">
		            <input value="{{ env('WAS_DEFAULT_REGION') }}" name="WAS_DEFAULT_REGION" type="text" class="form-control @error('WAS_DEFAULT_REGION') is-invalid @endif">
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">Wasabi Bucket</label>
		          <div class="col-sm-10">
		            <input value="{{ env('WAS_BUCKET') }}" name="WAS_BUCKET" type="text" class="form-control @error('WAS_BUCKET') is-invalid @endif">
		          </div>
		        </div>

						<hr />

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">Vultr Key</label>
		          <div class="col-sm-10">
		            <input value="{{ env('VULTR_ACCESS_KEY') }}" name="VULTR_ACCESS_KEY" type="text" class="form-control @error('VULTR_ACCESS_KEY') is-invalid @endif">
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">Vultr Secret</label>
		          <div class="col-sm-10">
		            <input value="{{ env('VULTR_SECRET_KEY') }}" name="VULTR_SECRET_KEY" type="text" class="form-control @error('VULTR_SECRET_KEY') is-invalid @endif">
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">Vultr Region</label>
		          <div class="col-sm-10">
		            <input value="{{ env('VULTR_REGION') }}" name="VULTR_REGION" type="text" class="form-control @error('VULTR_REGION') is-invalid @endif">
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">Vultr Bucket</label>
		          <div class="col-sm-10">
		            <input value="{{ env('VULTR_BUCKET') }}" name="VULTR_BUCKET" type="text" class="form-control @error('VULTR_BUCKET') is-invalid @endif">
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
