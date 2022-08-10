<div class="py-5 py-footer-large bg-dark-2 text-light">
      <footer class="container">
        <div class="row">

          <div class="col-md-3">
            <a href="{{ url('/') }}">
              <img src="{{ url('public/img', $settings->logo_light) }}" width="150">
            </a>

            @if ($settings->twitter != ''
              ||$settings->facebook != ''
              || $settings->instagram != ''
              || $settings->linkedin != ''
              || $settings->youtube != ''
              || $settings->pinterest != '')
            <span class="w-100 d-block mb-2">{{ __('misc.desc_footer_social') }}</span>
          @endif

            <ul class="list-inline list-social">

				@if ($settings->twitter != '')
				<li class="list-inline-item"><a href="{{$settings->twitter}}" target="_blank" class="ico-social"><i class="fab fa-twitter"></i></a></li>
				@endif

				@if ($settings->facebook != '')
				<li class="list-inline-item"><a href="{{$settings->facebook}}" target="_blank" class="ico-social"><i class="fab fa-facebook"></i></a></li>
				@endif

				@if ($settings->instagram != '')
				<li class="list-inline-item"><a href="{{$settings->instagram}}" target="_blank" class="ico-social"><i class="fab fa-instagram"></i></a></li>
				@endif

				@if ($settings->linkedin != '')
				<li class="list-inline-item"><a href="{{$settings->linkedin}}" target="_blank" class="ico-social"><i class="fab fa-linkedin"></i></a></li>
				@endif

				@if ($settings->youtube != '')
				<li class="list-inline-item"><a href="{{$settings->youtube}}" target="_blank" class="ico-social"><i class="fab fa-youtube"></i></a></li>
				@endif

				@if ($settings->pinterest != '')
				<li class="list-inline-item"><a href="{{$settings->pinterest}}" target="_blank" class="ico-social"><i class="fab fa-pinterest"></i></a></li>
				@endif
            </ul>

            <li>
              <div id="installContainer" class="display-none">
                <button class="btn btn-custom w-100 rounded-pill mb-4" id="butInstall" type="button">
                  <i class="bi-phone mr-1"></i> {{ __('misc.install_web_app') }}
                </button>
              </div>
            </li>
          </div>

          <div class="col-md-3">
            <h6 class="text-uppercase">{{trans('misc.about')}}</h6>
            <ul class="list-unstyled">
					@foreach (Helper::pages() as $page)
                      <li><a class="text-white text-decoration-none" href="{{url('page', $page->slug) }}">{{ $page->title }}</a></li>
					  @endforeach

            @if ($settings->link_blog != '')
					  <li><a class="text-white text-decoration-none" target="_blank" href="{{ $settings->link_blog }}">{{ trans('misc.blog') }}</a></li>
          @endif

            <li><a class="text-white text-decoration-none" href="{{ url('contact') }}">{{ trans('misc.contact') }}</a></li>

            </li></ul>
          </div>

          <div class="col-md-3">
            <h6 class="text-uppercase">{{trans('misc.categories')}}</h6>
            <ul class="list-unstyled">
			@foreach (Categories::where('mode','on')->orderBy('name')->take(6)->get() as $category)
            <li>
				<a class="text-white text-decoration-none" href="{{ url('category', $category->slug) }}">
                {{ Lang::has('categories.' . $category->slug) ? __('categories.' . $category->slug) : $category->name }}
              </a>
			  </li>
			@endforeach

			@if (Categories::count() > 6)
			 <li><a class="text-white text-decoration-none arrow" href="{{ url('categories') }}">{{ trans('misc.view_all') }}</a></li>
			@endif

			</ul>
          </div>

          <div class="col-md-3">
            <h6 class="text-uppercase">{{trans('misc.links')}}</h6>
            <ul class="list-unstyled">

          @if ($settings->sell_option == 'on')
          <li>
    			  <a class="text-white text-decoration-none" href="{{ url('photos/premium') }}">{{ trans('misc.premium') }}</a>
    			</li>
          @endif

          <li>
    			  <a class="text-white text-decoration-none" href="{{ url('featured') }}">{{ trans('misc.featured') }}</a>
    			</li>

          <li>
    			  <a class="text-white text-decoration-none" href="{{ url('collections') }}">{{ trans('misc.collections') }}</a>
    			</li>

			  @guest
			<li>
			  <a class="text-white text-decoration-none" href="{{ url('login') }}">{{ trans('auth.login') }}</a>
			  </li>

			  @if ($settings->registration_active == 1)
				<li>
				<a class="text-white text-decoration-none" href="{{ url('register') }}">{{ trans('auth.sign_up') }}</a>
				</li>
			  @endif

			  @else

			  @if (auth()->user()->role)
				<li>
				<a class="text-white text-decoration-none" href="{{ url('panel/admin') }}">{{ trans('admin.admin') }}</a>
				</li>
			  @endif

			  <li>
				<a class="text-white text-decoration-none" href="{{ url(auth()->user()->username) }}">{{ trans('users.my_profile') }}</a>
				</li>

				<li>
				<a class="text-white text-decoration-none" href="{{ url('logout') }}">{{ trans('users.logout') }}</a>
				</li>
			  @endguest

				<li class="dropdown mt-1">
          <div class="btn-group dropup">
            <a class="btn btn-outline-light rounded-pill mt-2 dropdown-toggle px-4" id="dropdownLang" href="javascript:;" data-bs-toggle="dropdown">
                  <i class="fa fa-globe me-1"></i>

  				  @foreach (Languages::orderBy('name')->get() as $languages)
                      	@if ($languages->abbreviation == config('app.locale'))
                          {{ $languages->name }}
                        @endif
                      @endforeach
                  </a>

          <div class="dropdown-menu dropdown-menu-macos">

					@foreach (Languages::orderBy('name')->get() as $languages)
						<a class="dropdown-item dropdown-lang @if ($languages->abbreviation == config('app.locale')) active  @endif" aria-labelledby="dropdownLang" @if ($languages->abbreviation != config('app.locale')) href="{{ url('lang', $languages->abbreviation) }}" @endif>
						 @if ($languages->abbreviation == config('app.locale'))
						 <i class="bi bi-check2 me-1"></i>
						 @endif

							{{ $languages->name }}
              @endforeach
                </a>
					 </div>
         </div><!-- dropup -->
				</li>
      </ul>
    </div>
  </div>
</footer>
</div>

      <footer class="py-2 bg-dark-3 text-muted">
        <div class="container">
          <div class="row">
            <div class="col-md-12 text-center">
              &copy; <?php echo date('Y'); ?> - {{ $settings->title }}, {{ __('emails.rights_reserved') }}
            </div>
            </div>
        </div>
      </footer>
