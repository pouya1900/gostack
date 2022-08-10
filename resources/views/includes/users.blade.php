@foreach($users as $user)

@php
if (auth()->check()) {
 	$followActive = auth()->user()->followActive($user->id);

	if ($followActive) {
		$textFollow   = trans('users.following');
		$icoFollow    = 'person-check';
		$activeFollow = 'custom';
    $btnFollowActive = 'btnFollowActive';
	} else {
		$textFollow   = trans('users.follow');
		$icoFollow    = 'person-plus';
		$activeFollow = 'outline-custom';
    $btnFollowActive = null;
	}
 }
 	$images = $user->images()->orderBy('id', 'desc')->take(3)->get();

 @endphp
<div class="col-md-4 mb-4">
<div class="card card-updates h-100 card-user-profile shadow-sm">
	<div class="card-cover" style="background: @if ($user->cover != '') url({{ Storage::url(config('path.cover').$user->cover) }})  @endif #505050 center center; background-size: cover;"></div>
	<div class="card-avatar">
		<a href="{{url($user->username)}}">
		<img src="{{Storage::url(config('path.avatar').$user->avatar)}}" width="95" height="95" alt="{{$user->name}}" class="img-user-small">
		</a>
	</div>
	<div class="card-body text-center">
			<h6 class="card-title pt-4">
				{{$user->name == '' ? $user->username : $user->name}} <small class="text-muted">{{ '@'.$user->username }}</small>
			</h6>

			<ul class="list-inline m-0 mb-2">
				<li class="list-inline-item"><i class="bi bi-people"></i> {{ Helper::formatNumber($user->followers()->count()) }}</li>
				<li class="list-inline-item"><i class="bi bi-images"></i> {{ Helper::formatNumber($user->images()->count()) }}</li>
			</ul>

			<div class="d-block mb-4 mt-3">
				<div class="row">

					<div class="col-4 px-1">
						<img src="{{ isset($images[0]) ? Storage::url(config('path.thumbnail').$images[0]->thumbnail) : asset('public/img/placeholder.jpg') }}" width="95" class="image-card-user me-1">
					</div>

					<div class="col-4 px-1">
						<img src="{{ isset($images[1]) ? Storage::url(config('path.thumbnail').$images[1]->thumbnail) : asset('public/img/placeholder.jpg') }}" width="95" class="image-card-user me-1">
					</div>

					<div class="col-4 px-1">
						<img src="{{ isset($images[2]) ? Storage::url(config('path.thumbnail').$images[2]->thumbnail) : asset('public/img/placeholder.jpg') }}" width="95" class="image-card-user me-1 me-1">
					</div>

				</div>
			</div>

      @if (auth()->check() && auth()->id() != $user->id)
			<button type="button" class="btn btn-1 btn-sm btn-{{ $activeFollow }} {{$btnFollowActive}} me-1 btnFollow" data-id="{{ $user->id }}" data-follow="{{ trans('users.follow') }}" data-following="{{ trans('users.following') }}">
				<i class="bi bi-{{$icoFollow}} me-1"></i> {{ $textFollow }}
			</button>
      @endif

			<a href="{{url($user->username)}}" class="btn btn-1 btn-sm btn-outline-custom">
				{{ trans('users.go_to_profile') }}
			</a>

	</div>
</div><!-- End Card -->
</div><!-- End col -->
@endforeach

@if ($users->hasPages() !== null)
    <div id="linkPagination">
      {{ $users->appends(
        [
        'sort' => request()->get('sort')
        ]
      )->onEachSide(0)->links() }}
    </div>
@endif
