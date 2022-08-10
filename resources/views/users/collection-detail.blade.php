@extends('layouts.app')

@section('title'){{ $title }}@endsection

@section('content')
<section class="section section-sm">

<div class="container">
  <div class="row">

  <div class="col-lg-12 py-5">
    <h1 class="mb-0">
    @if ($collectionData->type == 'private') <i class="fa fa-lock me-1 padlock-sm"></i> @endif {{ $collectionData->title }} ({{number_format($images->total())}})
    </h1>
    <p class="lead text-muted mt-1">
        <a href="{{url($user->username, 'collections')}}" class="text-dark">
          <img src="{{ Storage::url(config('path.avatar').$user->avatar) }}" width="32" height="32" class="rounded-circle me-1">

          {{$user->username}}
        </a>

      @if (auth()->check() && auth()->id() == $collectionData->user_id)

      <span class="float-end">
        <a class="text-muted btn btn-sm bg-white border me-2 e-none btn-category" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#collections">
  				{{trans('admin.edit')}}
  			</a>

        <a class="text-danger btn btn-sm bg-white border me-2 e-none btn-category actionDelete" data-url="{{url('collection/delete',$collectionData->id)}}" href="javascript:void(0);">
  				<i class="far fa-trash-alt me-1"></i> {{trans('admin.delete')}}
  			</a>
      </span>
    @endif

    </p>
    </div>

<!-- Col MD -->
<div class="col-md-12">

	@if ($images->total() != 0)

    <div class="dataResult">
       @include('includes.images')
       @include('includes.pagination-links')
     </div>

	  @else
	    		<h3 class="mt-0 fw-light">
	    		{{ trans('misc.collection_empty') }}
	    	</h3>
	  @endif
 </div><!-- /COL MD -->

 </div><!-- row -->
 </div><!-- container wrap-ui -->
</section>

@if (auth()->check() && auth()->id() == $collectionData->user_id)
<!-- Start Modal -->
<div class="modal fade" id="collections" tabindex="-1" role="dialog" aria-hidden="true">
 <div class="modal-dialog modal-fullscreen-sm-down">
   <div class="modal-content">
     <div class="modal-header border-0">
         <h5 class="modal-title" id="myModalLabel">
           {{ trans('admin.edit') }}
         </h5>
           <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div><!-- Modal header -->

      <div class="modal-body">

          <!-- form start -->
         <form method="POST" action="" enctype="multipart/form-data" id="editCollectionForm">
           <input type="hidden" name="_token" value="{{ csrf_token() }}">
           <input type="hidden" name="id" value="{{ $collectionData->id }}">

           <div class="form-floating mb-3">
            <input type="text" class="form-control" name="title" value="{{ $collectionData->title }}" id="titleCollection" id="titleCollection" placeholder="{{ trans('admin.title') }}">
            <label for="titleCollection">{{ trans('admin.title') }}</label>
          </div>

          <div class="form-check form-switch">
          <input class="form-check-input" name="type" type="checkbox" @if ($collectionData->type == 'private') checked="checked" @endif id="flexSwitchCheckDefault">
          <label class="form-check-label" for="flexSwitchCheckDefault">{{ trans('misc.private') }}</label>
        </div>

      <!-- Alert -->
     <div class="alert alert-danger py-2 alert-small display-none" id="dangerAlert">
       <ul class="list-unstyled m-0" id="showErrors"></ul>
     </div><!-- Alert -->

          <div class="btn-block text-end">
           <button type="submit" class="btn btn-custom" id="editCollection"><i></i> {{ trans('misc.save_changes') }}</button>
          </div>
        </form>
       </div><!-- Modal body -->
     </div><!-- Modal content -->
   </div><!-- Modal dialog -->
 </div><!-- Modal -->
 @endif
@endsection

@section('javascript')
<script type="text/javascript">
 $('#imagesFlex').flexImages({ rowHeight: 320 });

@if (auth()->check() && auth()->id() == $collectionData->user_id )

	 $(".actionDelete").click(function(e) {
	   	e.preventDefault();

	  var element = $(this);
		var url     = element.attr('data-url');

		element.blur();

		swal(
			{   title: "{{trans('misc.delete_confirm')}}",
			  type: "warning",
			  showLoaderOnConfirm: true,
			  showCancelButton: true,
			  confirmButtonColor: "#DD6B55",
			   confirmButtonText: "{{trans('misc.yes_confirm')}}",
			   cancelButtonText: "{{trans('misc.cancel_confirm')}}",
			    closeOnConfirm: false,
			    },
			    function(isConfirm){
			    	 if (isConfirm) {
			    	 	window.location.href = url;
			    	 	}
			    	 });
			 });
	@endif

</script>
@endsection
