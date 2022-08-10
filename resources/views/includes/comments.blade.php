@foreach ($comments_sql as $comment)
<div class="mb-3">
	 <div class="media media-comments position-relative" id="comment{{$comment->id}}">
			<span class="float-start me-3">
				<a href="{{url($comment->user()->username)}}">
				<img width="50" height="50" class="media-object rounded-circle" src="{{Storage::url(config('path.avatar').$comment->user()->avatar)}}">
			</a>
			</span>
			<div class="media-body media-body-comments  border-bottom pb-2">
				<h6 class="media-heading m-0">
					<a class="text-dark" href="{{url($comment->user()->username)}}">@if ($comment->user()->name != '') {{e($comment->user()->name)}} @else {{$comment->user()->username}} @endif</a>
					</h6>
				<p class="comments-p mentions-links">{!! Helper::checkText( $comment->reply ) !!}</p>

				<div class="d-block mt-2">
					<small class="timeAgo text-muted me-1" data="{{ date('c', strtotime( $comment->date )) }}"></small>

@if (auth()->check())

@php

$commentLike = CommentsLikes::where('user_id', '=', auth()->user()->id)
->where('comment_id', '=', $comment->id)
->where('status','1')->first();

 if ($commentLike) {
	$activeLike = 'bi bi-heart-fill';
	$likeActive = 'activeLikeComment';
 } else {
	$activeLike = 'bi bi-heart';
	$likeActive = null;
	}
@endphp

	<span class="likeComment {{$likeActive}}" data-id="{{$comment->id}}">
		<i class="{{$activeLike}}"></i>
	</span>

	@if ($comment->user_id == auth()->id() || auth()->id() == $response->user()->id)
	<span class="deleteComment c-pointer text-muted" data-id="{{$comment->id}}" title="{{trans('misc.delete')}}">
		<i class="bi bi-trash"></i>
	</span>
	@endif

	@endif
	{{-- End AuthCheck --}}

	<small data-id="{{$comment->id}}" class="float-end comments-likes like-small @if ($comment->total_likes()->count() == 0) display-none @endif">
		<span class="bi bi-heart me-1"></span>
		<span class="count">{{ $comment->total_likes()->count() }}</span>
	</small>

    			</div>
    		</div>
  		 </div><!-- media -->

	</div><!-- media box -->
@endforeach

	@if ($comments_sql->count() != 0 )
	    <div style="width: 100%">
	    	{{ $comments_sql->links() }}
	    	</div>
	    	@endif
