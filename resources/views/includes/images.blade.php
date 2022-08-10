<div id="{{ isset($imgFeatured) ? 'imagesFlexFeatured' : 'imagesFlex' }}" class="flex-images d-block">
	@foreach ($images as $image)

		@php
			$colors = explode(",", $image->colors);
			$color = $colors[0];

			if ($image->extension == 'png') {
				$background = 'background: url('.url('public/img/pixel.gif').') repeat center center #e4e4e4;';
			}  else {
				$background = 'background-color: #'.$color.';';
			}

			$stockImage = Stock::whereImagesId($image->id)->whereType('medium')->first();
			$resolution = explode('x', Helper::resolutionPreview($stockImage->resolution));
			$newWidth   = $resolution[0];
			$newHeight  = $resolution[1];
		@endphp


<a class="item hovercard" data-w="{{$newWidth}}" data-h="{{$newHeight}}" href="{{ url('photo', $image->id ) }}/{{str_slug($image->title)}}" style="{{$background}}">

	@if ($image->item_for_sale == 'sale')
		<small class="premium-crown">
			<i class="fa fa-crown text-warning" title="{{trans('misc.premium')}}"></i>
		</small>
	@endif

		<!-- hover-content -->
		<span class="hover-content">
			<span class="text-truncate title-hover-content" title="{{$image->title}}">
				@if ($image->featured == 'yes') <i class="bi bi-award" title="{{trans('misc.featured')}}"></i> @endif
				</span>

			<div class="sub-hover d-flex align-items-center">
				<div class="flex-shrink-0">
				  <img src="{{ Storage::url(config('path.avatar').$image->user()->avatar) }}"class="rounded-circle avatarUser" style="width: 32px; height: 32px;">
				</div>
				<div class="flex-grow-1 ms-3 text-truncate">
					<span class="d-block w-100 text-truncate">{{$image->user()->username}}</span>

					@if ($image->item_for_sale == 'sale')
					<span class="me-2 d-none d-lg-inline-block"><i class="bi bi-cart"></i> {{Helper::amountFormat($settings->default_price_photos ?: $image->price)}}</span>
					@endif
					<span class="me-2 d-none d-lg-inline-block"><i class="bi bi-heart"></i> {{Helper::formatNumber($image->likes()->count())}}</span>
					<span class="me-2 d-none d-lg-inline-block"><i class="bi bi-download"></i> {{Helper::formatNumber($image->downloads()->count())}}</span>
				</div>

			  </div>

		</span><!-- hover-content -->

			<img @if (! request()->ajax())class="previewImage d-none"@endif sizes="580px" srcset="{{ url('files/preview/'.$stockImage->resolution, $stockImage->name)}}?size=small 280w, {{ url('files/preview/'.$stockImage->resolution, $stockImage->name)}}?size=medium 480w" src="{{ url('files/preview/'.$stockImage->resolution, $stockImage->name) }}" />
		</a>
		@endforeach
	</div><!-- flex-images -->

	@if ($images->count() && request()->ajax())
	@include('includes.pagination-links')
@endif
