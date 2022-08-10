@extends('admin.layout')

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ __('misc.purchases') }} ({{$data->total()}})</span>
  </h5>

<div class="content">
	<div class="row">

		<div class="col-lg-12">

			<div class="card shadow-custom border-0">
				<div class="card-body p-lg-4">

          <div class="table-responsive p-0">
            <table class="table table-hover">
               <tbody>

               	@if( $data->total() !=  0 && $data->count() != 0 )
                   <tr>
                      <th class="active">{{ trans('misc.thumbnail') }}</th>
                      <th class="active">{{ trans('admin.title') }}</th>
                      <th class="active">{{ trans('misc.uploaded_by') }}</th>
                      <th class="active">{{ trans('misc.buyer') }}</th>
                      <th class="active">{{ trans('admin.type') }}</th>
                      <th class="active">{{ trans('misc.license') }}</th>
                      <th class="active">{{ trans('misc.price') }}</th>
                      <th class="active">{{ trans('misc.earnings') }} ({{ trans('admin.role_admin') }})</th>
                      <th class="active">{{ trans('admin.date') }}</th>
                    </tr><!-- /.TR -->


                  @foreach ($data as $purchase)

                    @php

                    if (null !== $purchase->images()) {

                      $image_photo = Storage::url(config('path.thumbnail').$purchase->images()->thumbnail);
                      $image_title = $purchase->images()->title;
                      $image_url   = url('photo', $purchase->images()->id);

                      $purchase_username = $purchase->user()->username;
                      $purchase_email = $purchase->user()->email;

                      $uploaded_by = $purchase->images()->user()->username;
                      $uploaded_by_url = url($uploaded_by);

                    } else {
                      $image_photo = null;

                      $_purchase_username = User::whereId($purchase->user_id)->first();
                      $purchase_username = $_purchase_username->username;

                      $_purchase_email = User::whereId($purchase->user_id)->first();
                      $purchase_email = $_purchase_email->email;
                    }

                    switch ($purchase->type) {
              			case 'small':
              				$type          = trans('misc.small_photo');
              				break;
              			case 'medium':
              				$type          = trans('misc.medium_photo');
              				break;
              			case 'large':
              				$type          = trans('misc.large_photo');
              				break;
                    case 'vector':
                        $type          = trans('misc.vector_graphic');
                        break;
                      }

                      switch ($purchase->license) {
                			case 'regular':
                				$license          = trans('misc.regular');
                				break;
                			case 'extended':
                				$license          = trans('misc.extended');
                				break;
                        }

                    @endphp

                    <tr>
                      <td>
												@if ($image_photo)
													<img src="{{$image_photo}}" class="rounded" width="50" onerror="" />
												@else
													{{ trans('misc.not_available') }}
												@endif

											</td>
                      <td>
										 @if ($image_photo)
												<a href="{{ $image_url }}" title="{{$image_title}}" target="_blank">{{ str_limit($image_title, 20, '...') }} <i class="bi-box-arrow-up-right"></i></a>
											@else
												{{ trans('misc.not_available') }}
											@endif
											</td>
                      <td>
												@if ($image_photo)
												<a href="{{$uploaded_by_url}}" target="_blank">{{$uploaded_by}} <i class="bi-box-arrow-up-right"></i></a>
											@else
												{{ trans('misc.not_available') }}
											@endif
											</td>
                      <td><a href="{{url($purchase_username)}}" target="_blank">{{$purchase_username}} <i class="bi-box-arrow-up-right"></i></a></td>
                      <td>{{ $type }}</td>
                      <td>{{$license}}</td>
                      <td>
												{{ Helper::amountFormatDecimal($purchase->price) }}

												@if ($purchase->mode == 'subscription')
                          <i class="fa fa-info-circle text-muted showTooltip" title="{{trans('misc.via_subscription')}}"></i>
                        @endif
											</td>
                      <td>{{ Helper::amountFormatDecimal($purchase->earning_net_admin) }}

                        @if ($purchase->referred_commission)
                          <span class="ms-1 text-muted showTooltip" title="{{trans('misc.referral_commission_applied')}}">
                            <i class="fa fa-info-circle"></i>
                          </span>
                        @endif
                      </td>
                      <td>{{ date('d M, Y', strtotime($purchase->date)) }}</td>
                    </tr><!-- /.TR -->
                    @endforeach

                    @else
                    	<h5 class="text-center p-5 text-muted fw-light m-0">{{ trans('misc.no_results_found') }}</h5>
                    @endif

                  </tbody>
                </table>

                </div><!-- /.table responsive -->

				 </div><!-- card-body -->
 			</div><!-- card  -->

      {{ $data->onEachSide(0)->links() }}
 		</div><!-- col-lg-12 -->

	</div><!-- end row -->
</div><!-- end content -->
@endsection

@section('javascript')

<script type="text/javascript"></script>
  @endsection
