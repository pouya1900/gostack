@extends('layouts.app')

@section('content')
<div class="container-fluid home-cover">
      <div class="mb-4 position-relative custom-pt-6">
        <div class="container px-5">

          @if ($settings->announcement != '')
            <div class="alert alert-{{$settings->type_announcement}} announcements display-none alert-dismissible fade show" role="alert">

              <h4 class="alert-heading"><i class="bi-megaphone me-2"></i> {{ trans('admin.announcements') }}</h4>

              <p class="update-text">
                {!! $settings->announcement !!}
              </p>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" id="closeAnnouncements">
                  <i class="bi bi-x-lg"></i>
                </button>
              </div><!-- end announcements -->
            @endif

          <h1 class="display-3 fw-bold text-white">{{ __('seo.welcome_text') }}</h1>
          <p class="col-md-8 fs-4 fw-bold text-white">{{ __('seo.welcome_subtitle') }}</p>
          <form action="{{ url('search') }}" method="get" class="position-relative">
            <i class="bi bi-search btn-search"></i>
            <input class="form-control form-control-lg ps-5 input-search-lg border-0 search-lg" type="text" name="q" autocomplete="off" placeholder="{{trans('misc.search')}}" required minlength="3">
          </form>

		  @if ($categoryPopular)
          <p class="mt-2 text-white linkCategoryPopular">
            {{trans('misc.popular_categories')}} {!! $categoryPopular !!}
          </p>
		  @endif

        </div>
      </div>
    </div><!-- container-fluid -->


<div class="container py-5 py-large">

	@if ($images->total() != 0)

  @if ($settings->show_images_index == 'featured' || $settings->show_images_index == 'both')

  <div class="btn-block text-center mb-5">
    <h3 class="m-0">{{trans('misc.featured_photos')}}</h3>
    <p>
      {{ trans('misc.featured_desc') }}
    </p>
  </div>

  @include('includes.images', ['images' => $featured, 'imgFeatured' => true])

  <div class="w-100 d-block text-center mt-5">
    <a href="{{ url('featured') }}" class="btn btn-lg btn-main rounded-pill btn-outline-custom px-4 arrow px-5">
      {{ trans('misc.view_all') }}
    </a>
  </div>
  @endif

  @if ($settings->show_images_index == 'latest' || $settings->show_images_index == 'both')
    <div class="btn-block text-center mb-5 @if ($settings->show_images_index == 'both') mt-5 @endif">
      <h3 class="m-0">{{trans('misc.recent_photos')}}</h3>
      <p>
        {{ trans('misc.latest_desc') }}
      </p>
  </div>

  @include('includes.images')

  <div class="w-100 d-block text-center mt-5">
    <a href="{{ url('latest') }}" class="btn btn-lg btn-main rounded-pill btn-outline-custom px-4 arrow px-5">
      {{ trans('misc.view_all') }}
    </a>
  </div>
  @endif

	@else
	<h4 class="text-center">
      <div class="d-block w-100 display-2">
        <i class="bi bi-images"></i>
      </div>

		{{ trans('misc.no_images_published') }}
	</h4>

  <div class="w-100 d-block text-center mt-3">
    <a href="{{ url('upload') }}" class="btn btn-lg btn-main rounded-pill btn-outline-custom px-4 arrow px-5">
      {{ trans('users.upload') }}
    </a>
  </div>
	@endif

  @if ($settings->google_adsense && $settings->google_ads_index == 'on' && $settings->google_adsense_index != '')
    <div class="col-md-12 mt-3">
      {!! $settings->google_adsense_index !!}
    </div>
  @endif
</div><!-- container photos -->

    @if ($images->total() != 0)
    <section class="section py-5 py-large bg-light">
      <div class="container">
        <div class="row align-items-center">
        <div class="col-12 col-lg-7 text-center mb-3 px-5">
          <img src="{{ url('public/img', $settings->img_section) }}" class="img-fluid">
        </div>
        <div class="col-12 col-lg-5 text-lg-start text-center">
          <h1 class="m-0 card-profile">{{ trans('misc.title_section_home') }}</h1>
          <div class="col-12 p-0">
            <p class="py-4 m-0 text-muted">{{ trans('misc.desc_section_home') }}</p>
          </div>
          <a href="{{ url('latest') }}" class="btn btn-lg btn-main rounded-pill btn-outline-custom  px-4 arrow">
            {{ trans('misc.explore') }}
          </a>
        </div>
      </div>
      </div>
    </section>
    @endif

    @if ($settings->show_counter == 'on')
    <section class="section py-2 bg-dark text-white">
      <div class="container">
        <div class="row">
          <div class="col-md-4">
            <div class="d-flex py-3 my-1 my-lg-0 justify-content-center">
              <span class="me-3 display-4"><i class="bi bi-people align-baseline"></i></span>
              <div>
                <h3 class="mb-0"><span class="counter">{{ User::whereStatus('active')->count() }}</span></h3>
                <h5>{{trans('misc.members')}}</h5>
              </div>
            </div>

          </div>
          <div class="col-md-4">
            <div class="d-flex py-3 my-1 my-lg-0 justify-content-center">
              <span class="me-3 display-4"><i class="bi bi-download align-baseline"></i></span>
              <div>
                <h3 class="mb-0"><span class="counter">{{ Downloads::count() }}</span></h3>
                <h5 class="font-weight-light">{{trans('misc.downloads')}}</h5>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="d-flex py-3 my-1 my-lg-0 justify-content-center">
              <span class="me-3 display-4"><i class="bi bi-images align-baseline"></i></span>
              <div>
                <h3 class="mb-0"> <span class="counterStats">{{ Images::whereStatus('active')->count() }}</span></h3>
                <h5 class="font-weight-light">{{trans('misc.stock_photos')}}</h5>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    @endif

	@if ($settings->show_categories_index == 'on')
    <section class="section py-5 py-large">
      <div class="container">
        <div class="btn-block text-center mb-5">
          <h3 class="m-0">{{trans('misc.categories')}}</h3>
          <p>
            {{trans('misc.browse_by_category')}}
          </p>
        </div>

        <div class="row">

		@include('includes.categories-listing')

    @if ($categories->total() > 4)
    <div class="w-100 d-block text-center mt-4">
      <a href="{{ url('categories') }}" class="btn btn-lg btn-main rounded-pill btn-outline-custom px-4 arrow px-5">
        {{ trans('misc.view_all') }}
      </a>
    </div>
    @endif

</section>
@endif

@endsection

@section('javascript')
	<script type="text/javascript">

  $('#imagesFlex').flexImages({ rowHeight: 320, maxRows: 8, truncate: true });
  $('#imagesFlexFeatured').flexImages({ rowHeight: 320, maxRows: 8, truncate: true });

		@if (session('success_verify'))
		swal({
			title: "{{ trans('misc.welcome') }}",
			text: "{{ trans('users.account_validated') }}",
			type: "success",
			confirmButtonText: "{{ trans('users.ok') }}"
			});
		@endif

		@if (session('error_verify'))
		swal({
			title: "{{ trans('misc.error_oops') }}",
			text: "{{ trans('users.code_not_valid') }}",
			type: "error",
			confirmButtonText: "{{ trans('users.ok') }}"
			});
		@endif

	</script>
@endsection
