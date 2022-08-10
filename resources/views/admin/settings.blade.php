@extends('admin.layout')

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ __('admin.general_settings') }}</span>
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

      <form method="POST" action="{{ url('panel/admin/settings') }}" enctype="multipart/form-data">
        @csrf

       <div class="row mb-3">
         <label class="col-sm-2 col-form-label text-lg-end">{{ trans('admin.name_site') }}</label>
         <div class="col-sm-10">
           <input type="text" value="{{ $settings->title }}" name="title" class="form-control">
         </div>
       </div><!-- end row -->

       <div class="row mb-3">
         <label class="col-sm-2 col-form-labe text-lg-end">{{ trans('admin.link_terms') }}</label>
         <div class="col-sm-10">
           <input type="text" value="{{ $settings->link_terms }}" name="link_terms" class="form-control">
         </div>
       </div><!-- end row -->

       <div class="row mb-3">
         <label class="col-sm-2 col-form-labe text-lg-end">{{ trans('admin.link_privacy') }}</label>
         <div class="col-sm-10">
           <input type="text" value="{{ $settings->link_privacy }}" name="link_privacy" class="form-control">
           <small class="d-block"></small>
         </div>
       </div><!-- end row -->

       <div class="row mb-3">
         <label class="col-sm-2 col-form-labe text-lg-end">{{ trans('misc.link_license') }}</label>
         <div class="col-sm-10">
           <input type="text" value="{{ $settings->link_license }}" name="link_license" class="form-control">
         </div>
       </div><!-- end row -->

			 <div class="row mb-3">
         <label class="col-sm-2 col-form-labe text-lg-end">{{ trans('misc.link_blog') }}</label>
         <div class="col-sm-10">
           <input type="text" value="{{ $settings->link_blog }}" name="link_blog" class="form-control" placeholder="https://blog.wordpress.com">
         </div>
       </div><!-- end row -->

			 <div class="row mb-3">
				 <label class="col-sm-2 col-form-labe text-lg-end">{{ trans('misc.default_language') }}</label>
				 <div class="col-sm-10">
					 <select name="default_language" class="form-select">
						 @foreach (Languages::orderBy('name')->get() as $language)
 							<option @if ($language->abbreviation == env('DEFAULT_LOCALE')) selected="selected" @endif value="{{$language->abbreviation}}">{{ $language->name }}</option>
 						@endforeach
					</select>
					<small class="d-block">{{ __('misc.default_language_info') }}</small>
				 </div>
			 </div>

       <fieldset class="row mb-3">
         <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ trans('misc.sell_option') }}</legend>
         <div class="col-sm-10">
           <div class="form-check">
             <input class="form-check-input" type="radio" name="sell_option" id="radio1" @if ($settings->sell_option == 'on') checked="checked" @endif value="on">
             <label class="form-check-label" for="radio1">
               On ({{ trans('misc.members_can_sell') }})
             </label>
           </div>
           <div class="form-check">
             <input class="form-check-input" type="radio" name="sell_option" id="radio2" @if ($settings->sell_option == 'off') checked="checked" @endif value="off">
             <label class="form-check-label" for="radio2">
               Off ({{ trans('misc.members_cant_sell') }})
             </label>
           </div>
           <div class="alert alert-info py-2 mb-0">
            <i class="bi-info-circle me-2"></i> {{ trans('misc.notice_sell_option') }}
           </div>
         </div>
       </fieldset><!-- end row -->

       <fieldset class="row mb-3">
         <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ trans('misc.who_can_sell') }}</legend>
         <div class="col-sm-10">
           <div class="form-check">
             <input class="form-check-input" type="radio" name="who_can_sell" id="radio3" @if ($settings->who_can_sell == 'all') checked="checked" @endif value="all">
             <label class="form-check-label" for="radio3">
               {{ trans('misc.all_members') }}
             </label>
           </div>
           <div class="form-check">
             <input class="form-check-input" type="radio" name="who_can_sell" id="radio4" @if ($settings->who_can_sell == 'admin') checked="checked" @endif value="admin">
             <label class="form-check-label" for="radio4">
               {{ trans('misc.only_admin') }}
             </label>
           </div>
         </div>
       </fieldset><!-- end row -->

       <fieldset class="row mb-3">
         <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ trans('misc.who_can_upload') }}</legend>
         <div class="col-sm-10">
           <div class="form-check">
             <input class="form-check-input" type="radio" name="who_can_upload" id="radio8" @if ($settings->who_can_upload == 'all') checked="checked" @endif value="all">
             <label class="form-check-label" for="radio8">
               {{ trans('misc.all_members') }}
             </label>
           </div>
           <div class="form-check">
             <input class="form-check-input" type="radio" name="who_can_upload" id="radio9" @if ($settings->who_can_upload == 'admin') checked="checked" @endif value="admin">
             <label class="form-check-label" for="radio9">
               {{ trans('misc.only_admin') }}
             </label>
           </div>
         </div>
       </fieldset><!-- end row -->

       <fieldset class="row mb-3">
         <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ trans('admin.show_images_index') }}</legend>
         <div class="col-sm-10">
           <div class="form-check">
             <input class="form-check-input" type="radio" name="show_images_index" id="radio5" @if ($settings->show_images_index == 'latest') checked="checked" @endif value="latest">
             <label class="form-check-label" for="radio5">
               {{ trans('misc.latest') }}
             </label>
           </div>
           <div class="form-check">
             <input class="form-check-input" type="radio" name="show_images_index" id="radio6" @if ($settings->show_images_index == 'featured') checked="checked" @endif value="featured">
             <label class="form-check-label" for="radio6">
               {{ trans('misc.featured') }}
             </label>
           </div>
           <div class="form-check">
            <input class="form-check-input" type="radio" name="show_images_index" id="radioBoth" @if ($settings->show_images_index == 'both') checked="checked" @endif value="both">
            <label class="form-check-label" for="radioBoth">
              {{ trans('admin.both') }}
            </label>
          </div>
         </div>
       </fieldset><!-- end row -->

       <fieldset class="row mb-3">
         <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ trans('admin.email_verification') }}</legend>
         <div class="col-sm-10">
           <div class="form-check form-switch form-switch-md">
            <input class="form-check-input" type="checkbox" name="email_verification" @if ($settings->email_verification == '1') checked="checked" @endif value="1" role="switch">
          </div>
         </div>
       </fieldset><!-- end row -->

       <fieldset class="row mb-3">
         <legend class="col-form-label col-sm-2 pt-0 text-lg-end">Captcha</legend>
         <div class="col-sm-10">
           <div class="form-check form-switch form-switch-md">
            <input class="form-check-input" type="checkbox" name="captcha" @if ($settings->captcha == 'on') checked="checked" @endif value="1" role="switch">
          </div>
         </div>
       </fieldset><!-- end row -->

       <fieldset class="row mb-3">
         <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ trans('admin.new_registrations') }}</legend>
         <div class="col-sm-10">
           <div class="form-check form-switch form-switch-md">
            <input class="form-check-input" type="checkbox" name="registration_active" @if ($settings->registration_active == '1') checked="checked" @endif value="1" role="switch">
          </div>
         </div>
       </fieldset><!-- end row -->

       <fieldset class="row mb-3">
         <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ trans('admin.show_watermark') }}</legend>
         <div class="col-sm-10">
           <div class="form-check form-switch form-switch-md">
            <input class="form-check-input" type="checkbox" name="show_watermark" @if ($settings->show_watermark == '1') checked="checked" @endif value="1" role="switch">
          </div>
         </div>
       </fieldset><!-- end row -->

       <fieldset class="row mb-3">
         <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ trans('misc.allow_free_photos') }}</legend>
         <div class="col-sm-10">
           <div class="form-check form-switch form-switch-md">
            <input class="form-check-input" type="checkbox" name="free_photo_upload" @if ($settings->free_photo_upload == 'on') checked="checked" @endif value="on" role="switch">
          </div>
         </div>
       </fieldset><!-- end row -->

       <fieldset class="row mb-3">
         <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ trans('misc.show_counter') }}</legend>
         <div class="col-sm-10">
           <div class="form-check form-switch form-switch-md">
            <input class="form-check-input" type="checkbox" name="show_counter" @if ($settings->show_counter == 'on') checked="checked" @endif value="on" role="switch">
          </div>
         </div>
       </fieldset><!-- end row -->

       <fieldset class="row mb-3">
         <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ trans('misc.show_categories_index') }}</legend>
         <div class="col-sm-10">
           <div class="form-check form-switch form-switch-md">
            <input class="form-check-input" type="checkbox" name="show_categories_index" @if ($settings->show_categories_index == 'on') checked="checked" @endif value="on" role="switch">
          </div>
         </div>
       </fieldset><!-- end row -->

       <fieldset class="row mb-3">
         <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ trans('admin.google_ads_index') }}</legend>
         <div class="col-sm-10">
           <div class="form-check form-switch form-switch-md">
            <input class="form-check-input" type="checkbox" name="google_ads_index" @if ($settings->google_ads_index == 'on') checked="checked" @endif value="on" role="switch">
          </div>
         </div>
       </fieldset><!-- end row -->

			 <fieldset class="row mb-3">
         <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ trans('misc.referral_system') }}</legend>
         <div class="col-sm-10">
           <div class="form-check form-switch form-switch-md">
            <input class="form-check-input" type="checkbox" name="referral_system" @if ($settings->referral_system == 'on') checked="checked" @endif value="on" role="switch">
          </div>
         </div>
       </fieldset><!-- end row -->

			 <fieldset class="row mb-3">
         <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ trans('misc.comment_system') }}</legend>
         <div class="col-sm-10">
           <div class="form-check form-switch form-switch-md">
            <input class="form-check-input" type="checkbox" name="comments" @if ($settings->comments) checked="checked" @endif value="1" role="switch">
          </div>
         </div>
       </fieldset><!-- end row -->

       <fieldset class="row mb-3">
         <legend class="col-form-label col-sm-2 pt-0 text-lg-end">Lightbox</legend>
         <div class="col-sm-10">
           <div class="form-check form-switch form-switch-md">
            <input class="form-check-input" type="checkbox" name="lightbox" @if ($settings->lightbox == 'on') checked="checked" @endif value="on" role="switch">
          </div>
         </div>
       </fieldset><!-- end row -->

			 <fieldset class="row mb-3">
         <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ __('misc.cookie_policy_banner') }}</legend>
         <div class="col-sm-10">
           <div class="form-check form-switch form-switch-md">
            <input class="form-check-input" type="checkbox" name="banner_cookies" @if ($settings->banner_cookies) checked="checked" @endif value="1" role="switch">
          </div>
         </div>
       </fieldset><!-- end row -->

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
