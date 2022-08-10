@extends('layouts.app')

@section('title') {{ trans('users.notifications') }} - @endsection

@section('content')
	<section class="section section-sm">

	    <div class="container">
	      <div class="row justify-content-center text-center mb-sm">
	        <div class="col-lg-7 py-5">
	          <h2 class="mb-0 font-montserrat">
	            <i class="far fa-bell mr-2"></i> {{trans('users.notifications')}}

							@if ($sql->count() !=  0)
	            <small>
	              <button class="btn btn-lg align-baseline p-0 e-none btn-link delete-notifications" type="button">
									<i class="fa fa-trash-alt"></i>
								</button>
	            </small>
						@endif
	          </h2>
	        </div>
	      </div><!-- row -->

<div class="container">

	<div class="row justify-content-center">
	<!-- Col MD -->
	<div class="col-md-7">

	<?php

 if ($sql->count() !=  0) {

	  foreach ($sql as $key) {

		$url_photo = url('photo', $key->id);
		$url_video = url('video', $key->id);
		$notyNormal = true;

		switch ($key->type) {
			case 1:
				$action          = trans('users.followed_you');
				$icoDefault      = '<i class="icon icon-User ico-btn-followed"></i>';
				$title           = null;
				$linkDestination = false;
				break;
			case 2:
				$action          = trans('users.like_you_photo');
				$icoDefault      = '<i class="icon-heart ico-btn-like"></i>';
				$title           = $key->title;
				$linkDestination = $url_photo;
				break;
			case 3:
				$action          = trans('users.comment_you_photo');
				$icoDefault      = '<i class="icon-bubble"></i>';
				$title           = $key->title;
				$linkDestination = $url_photo;
				break;

			case 4:
				$action          = trans('users.liked_your_comment');
				$icoDefault      = '<i class="icon-heart ico-btn-like"></i>';
				$title           = $key->title;
				$linkDestination = $url_photo;
				break;

			case 5:
				$action          = trans('misc.has_bought');
				$icoDefault      = '<i class="fa fa-shopping-cart"></i>';
				$title           = $key->title;
				$linkDestination = $url_photo;
				break;

			case 6:
				$action          = trans('misc.referrals_made');
				$linkDestination = url('my/referrals');
				$title           = trans('misc.transaction');
				$iconNotify      = 'bi bi-person-plus';
				$notyNormal      = false;
				break;

			case 7:
				$action          = trans('misc.video_processed_successfully');
				$linkDestination = $url_video;
				$title           = trans('misc.go_to_video');
				$iconNotify      = 'bi bi-play-circle';
				$notyNormal      = false;
				break;
		}

?>
				<!-- Start -->
				<div class="card mb-3">
        	<div class="card-body">
        	<div class="media">

						@if ($notyNormal)
						<span class="rounded-circle me-3">
        			<a href="{{url($key->username)}}">
        				<img src="{{Storage::url(config('path.avatar').$key->avatar)}}" class="rounded-circle" width="60" height="60">
        				</a>
        		</span>

					@else
						<span class="rounded-circle me-3">
							<span class="icon-notify">
								<i class="{{ $iconNotify }}"></i>
							</span>
					</span>
					@endif

        		<div class="d-inline-block text-muted">
							<h6 class="mb-0 font-montserrat">

							@if ($notyNormal)
        					<a href="{{url($key->username)}}">
        					{{ $key->name ? $key->name : $key->username}}
        				</a>
							@endif

								{{$action}} @if( $linkDestination != false ) <a href="{{url($linkDestination)}}">{{$title}}</a> @endif
              </h6>

        				<small class="timestamp timeAgo text-muted" data="{{date('c', strtotime($key->created_at))}}"></small>
        		</div><!-- media body -->
        	</div><!-- media -->
        </div><!-- card body -->
        </div>
				<!-- End -->

				<?php }//foreach
				} // != 0
			?>

				@if ($sql->count() == 0)

				<h3 class="mt-0 fw-light text-center">
					<span class="w-100 d-block mb-2 display-1 text-muted">
							<i class="bi-bell-slash"></i>
						</span>

					{{ trans('misc.no_notifications') }}
				</h3>

				@endif

			@if ($sql->lastPage() > 1)
				{{ $sql->onEachSide(0)->links() }}
			@endif

	</div><!-- /COL MD -->
	</div><!-- row -->
</div><!-- container -->
</section>
@endsection

@section('javascript')

<script type="text/javascript">
	//<<<---------- Delete Account
	$(".delete-notifications").click(function(e) {
		e.preventDefault();

		var element = $(this);
		var url = '{{url("notifications/delete")}}';

		swal({
			title : "{{trans('misc.delete_confirm')}}",
			text : "{{trans('misc.confirm_delete_all_notifications')}}",
			type : "warning",
			showLoaderOnConfirm : true,
			showCancelButton : true,
			confirmButtonColor : "#DD6B55",
			confirmButtonText : "{{trans('misc.yes_confirm')}}",
			cancelButtonText : "{{trans('misc.cancel_confirm')}}",
			closeOnConfirm : false,
		}, function(isConfirm) {
			if (isConfirm) {
				window.location.href = url;
			}
		});

	});
</script>
@endsection
