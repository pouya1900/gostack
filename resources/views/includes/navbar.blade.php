<header class="py-3 shadow-sm fixed-top bg-white" id="header">
        <div class="container-fluid d-grid gap-3 px-4 align-items-center" style="grid-template-columns: 0fr 2fr;">

            <a href="{{ url('/') }}" class="d-flex align-items-center col-lg-4 link-dark text-decoration-none fw-bold display-6">
              <img src="{{ url('public/img', $settings->logo) }}" class="logo d-none d-lg-block" width="110" />
              <img src="{{ url('public/img', $settings->favicon) }}" class="logo d-block d-lg-none" height="32" />
            </a>

          <div class="d-flex align-items-center">

            <form action="{{ url('search') }}" method="get" class="w-100 me-3 position-relative">
              <i class="bi bi-search btn-search bar-search"></i>
              <input type="text" class="form-control rounded-pill ps-5 input-search search-navbar" name="q" autocomplete="off" placeholder="{{trans('misc.search')}}" required minlength="3">
            </form>

            <!-- Start Nav -->
            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0 navbar-session">

              @if (Plans::whereStatus('1')->count() != 0 && $settings->sell_option == 'on')
                <li><a href="{{url('pricing')}}" class="nav-link px-2 link-dark">{{trans('misc.pricing')}}</a></li>
              @endif


              @auth
                <li><a href="{{url('feed')}}" class="nav-link px-2 link-dark">{{trans('misc.feed')}}</a></li>
              @endauth

              <li class="dropdown">
                <a href="javascript:void(0);" class="nav-link px-2 link-dark dropdown-toggle" id="dropdownExplore" data-bs-toggle="dropdown" aria-expanded="false">
                {{trans('misc.explore')}}
              </a>
              <ul class="dropdown-menu dropdown-menu-macos dropdown-menu-lg-end arrow-dm" aria-labelledby="dropdownExplore">
                <li><a class="dropdown-item" href="{{ url('members') }}"><i class="bi bi-people me-2"></i> {{ trans('misc.members') }}</a></li>
                <li><a class="dropdown-item" href="{{ url('collections') }}"><i class="bi bi-plus-square me-2"></i> {{ trans('misc.collections') }}</a></li>

                @if ($settings->sell_option == 'on')
                <li><a class="dropdown-item" href="{{ url('photos/premium') }}"><i class="fa fa-crown me-2 text-warning"></i> {{ trans('misc.premium') }}</a></li>
                @endif

                <li><hr class="dropdown-divider"></li>

                <li><a class="dropdown-item" href="{{ url('featured') }}">{{ trans('misc.featured') }}</a></li>
                <li><a class="dropdown-item" href="{{ url('popular') }}">{{ trans('misc.popular') }}</a></li>
                <li><a class="dropdown-item" href="{{ url('latest') }}">{{ trans('misc.latest') }}</a></li>
                @if ($settings->comments)
                <li><a class="dropdown-item" href="{{ url('most/commented') }}">{{trans('misc.most_commented')}}</a></li>
              @endif
                <li><a class="dropdown-item" href="{{ url('most/viewed') }}">{{trans('misc.most_viewed')}}</a></li>
                <li><a class="dropdown-item" href="{{ url('most/downloads') }}">{{trans('misc.most_downloads')}}</a></li>
              </ul>
              </li>

              <li class="dropdown">
                <a href="javascript:void(0);" class="nav-link px-2 link-dark dropdown-toggle" id="dropdownExplore" data-bs-toggle="dropdown" aria-expanded="false">
                  {{trans('misc.categories')}}
                </a>
                <ul class="dropdown-menu dropdown-menu-macos dropdown-menu-lg-end arrow-dm" aria-labelledby="dropdownCategories">

                @foreach (Categories::whereMode('on')->orderBy('name')->take(5)->get() as $category)
                  <li>
                  <a class="dropdown-item" href="{{ url('category', $category->slug) }}">
                  {{ Lang::has('categories.' . $category->slug) ? __('categories.' . $category->slug) : $category->name }}
                    </a>
                  </li>
                  @endforeach

                  @if (Categories::count() > 5)
                  <li>
                    <a class="dropdown-item arrow" href="{{ url('categories') }}">
                      <strong>{{ trans('misc.view_all') }}</strong>
                      </a>
                    </li>
                    @endif
                </ul>
              </li>

              @auth
              <li class="position-relative">
              <span class="noti_notifications notify @if (auth()->user()->unseenNotifications()) d-block @else display-none @endif">
              {{ auth()->user()->unseenNotifications() }}
              </span>

              <a href="{{ url('notifications') }}" class="nav-link px-2 link-dark"><i class="bi bi-bell me-2"></i></a>
              </li>

              @if (auth()->user()->authorized_to_upload == 'yes' || auth()->user()->isSuperAdmin())
              <li>
                <a href="{{ url('upload') }}" class="btn btn-custom me-4 animate-up-2 d-none d-lg-block" title="{{ trans('users.upload') }}">
                  <strong>{{ trans('users.upload') }}</strong>
                </a>
              </li>
              @endif

              @endauth

            </ul><!-- End Nav -->

                @guest
                  <a class="btn btn-custom ms-2 animate-up-2 d-none d-lg-block" href="{{ url('login') }}">
                  <strong>{{ trans('auth.login') }}</strong>
                  </a>
                @endguest


            @auth
            <div class="flex-shrink-0 dropdown">

              <a href="javascript:void(0);" class="d-block link-dark text-decoration-none" id="dropdownUser2" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="{{ Storage::disk('default')->url(config('path.avatar').auth()->user()->avatar) }}" width="32" height="32" class="rounded-circle avatarUser">
              </a>
              <ul class="dropdown-menu dropdown-menu-macos arrow-dm" aria-labelledby="dropdownUser2">
                @include('includes.menu-dropdown')
              </ul>

            </div>
            @endauth

            <a class="ms-3 toggle-menu d-block d-lg-none text-dark fs-3" data-bs-toggle="offcanvas" data-bs-target="#offcanvas" href="#">
            <i class="bi-list"></i>
            </a>

          </div><!-- d-flex -->
        </div><!-- container-fluid -->
      </header>

    <div class="offcanvas offcanvas-end w-75" tabindex="-1" id="offcanvas" data-bs-keyboard="false" data-bs-backdrop="false">
    <div class="offcanvas-header">
        <span class="offcanvas-title" id="offcanvas">
          <img src="{{ url('public/img', $settings->logo) }}" class="logo" width="100" />
        </span>
        <button type="button" class="btn-close text-reset close-menu-mobile" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body px-0">
        <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-start" id="menu">

          @if (Plans::whereStatus('1')->count() != 0 && $settings->sell_option == 'on')
            <li>
              <a href="{{url('pricing')}}" class="nav-link link-dark text-truncate">
              {{trans('misc.pricing')}}
            </a>
          </li>
          @endif

          @auth
            <li>
            <a href="{{url('feed')}}" class="nav-link link-dark text-truncate">
              {{trans('misc.feed')}}
            </a>
            </li>
          @endauth

            <li>
                <a href="#explore" data-bs-toggle="collapse" class="nav-link text-truncate link-dark dropdown-toggle">
                    {{trans('misc.explore')}}
                  </a>
            </li>

            <div class="collapse ps-3" id="explore">

              <li><a class="nav-link text-truncate text-muted" href="{{ url('members') }}"><i class="bi bi-people me-2"></i> {{ trans('misc.members') }}</a></li>
              <li><a class="nav-link text-truncate text-muted" href="{{ url('collections') }}"><i class="bi bi-plus-square me-2"></i> {{ trans('misc.collections') }}</a></li>

              @if ($settings->sell_option == 'on')
              <li><a class="nav-link text-truncate text-muted" href="{{ url('photos/premium') }}"><i class="fa fa-crown me-2 text-warning"></i> {{ trans('misc.premium') }}</a></li>
              @endif

              <li><a class="nav-link text-truncate text-muted" href="{{ url('featured') }}">{{ trans('misc.featured') }}</a></li>
              <li><a class="nav-link text-truncate text-muted" href="{{ url('popular') }}">{{ trans('misc.popular') }}</a></li>
              <li><a class="nav-link text-truncate text-muted" href="{{ url('latest') }}">{{ trans('misc.latest') }}</a></li>
              @if ($settings->comments)
              <li><a class="nav-link text-truncate text-muted" href="{{ url('most/commented') }}">{{trans('misc.most_commented')}}</a></li>
            @endif
              <li><a class="nav-link text-truncate text-muted" href="{{ url('most/viewed') }}">{{trans('misc.most_viewed')}}</a></li>
              <li><a class="nav-link text-truncate text-muted" href="{{ url('most/downloads') }}">{{trans('misc.most_downloads')}}</a></li>
            </div>

            <li>
                <a href="#categories" data-bs-toggle="collapse" class="nav-link text-truncate link-dark dropdown-toggle">
                    {{trans('misc.categories')}}
                  </a>
            </li>

            <div class="collapse ps-3" id="categories">
              @foreach (Categories::whereMode('on')->orderBy('name')->take(5)->get() as $category)
                <li>
                <a class="nav-link text-truncate text-muted" href="{{ url('category', $category->slug) }}">
                {{ Lang::has('categories.' . $category->slug) ? __('categories.' . $category->slug) : $category->name }}
                  </a>
                </li>
                @endforeach

                @if (Categories::count() > 5)
                <li>
                  <a class="nav-link text-truncate text-muted arrow" href="{{ url('categories') }}">
                    <strong>{{ trans('misc.view_all') }}</strong>
                    </a>
                  </li>
                  @endif
            </div>

          @guest
            <li class="p-3 w-100">
              <a href="{{ url('login') }}" class="btn btn-custom d-block w-100 animate-up-2" title="{{ trans('auth.login') }}">
                <strong>{{ trans('auth.login') }}</strong>
              </a>
            </li>
          @endguest
        </ul>
    </div>
</div>

@auth
<div class="menuMobile w-100 d-lg-none d-sm-block bg-white shadow-lg p-3 border-top">
	<ul class="list-inline d-flex m-0 text-center">

				<li class="flex-fill">
					<a class="p-3 btn-mobile" href="{{ url('home') }}">
						<i class="bi-house{{ request()->is('/') ? '-fill' : null }} icon-navbar"></i>
					</a>
				</li>

				<li class="flex-fill">
					<a class="p-3 btn-mobile" href="{{ url('latest') }}">
						<i class="bi-compass{{ request()->is('latest') ? '-fill' : null }} icon-navbar"></i>
					</a>
				</li>

        @if (auth()->user()->authorized_to_upload == 'yes' || auth()->user()->isSuperAdmin())
          <li class="flex-fill">
  					<a class="p-3 btn-mobile" href="{{ url('upload') }}">
  						<i class="bi-plus-circle{{ request()->is('upload') ? '-fill' : null }} icon-navbar"></i>
  					</a>
  				</li>
        @endif

			<li class="flex-fill position-relative">
        <span class="noti_notifications notify notify-mobile d-lg-none @if (auth()->user()->unseenNotifications()) d-block @else display-none @endif">
        {{ auth()->user()->unseenNotifications() }}
        </span>

				<a href="{{ url('notifications') }}" class="p-3 btn-mobile position-relative">
					<i class="bi-bell{{ request()->is('notifications') ? '-fill' : null }} icon-navbar"></i>
				</a>
			</li>

      <li class="flex-fill">
				<a href="{{ url(auth()->user()->username) }}" class="p-3 btn-mobile position-relative">

					<i class="bi-person{{ request()->is(auth()->user()->username) ? '-fill' : null }} icon-navbar"></i>
				</a>
			</li>

			</ul>
</div>

@endauth
