@extends('admin.layout')

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ __('admin.subscriptions') }} ({{$subscriptions->total()}})</span>
  </h5>

<div class="content">
	<div class="row">

		<div class="col-lg-12">

			<div class="card shadow-custom border-0">
				<div class="card-body p-lg-4">

					<div class="table-responsive p-0">
						<table class="table table-hover">
						 <tbody>

               @if ($subscriptions->total() !=  0 && $subscriptions->count() != 0)
                  <tr>
                     <th class="active">ID</th>
                     <th class="active">{{ trans('admin.subscriber') }}</th>
                     <th class="active">{{ trans('misc.plan') }}</th>
                     <th class="active">{{ trans('misc.billed') }}</th>
										 <th class="active">{{ trans('admin.date') }}</th>
										 <th class="active">{{ trans('misc.ends_at') }}</th>
                     <th class="active">{{ trans('admin.status') }}</th>
                   </tr>

                 @foreach ($subscriptions as $subscription)
                   <tr>
                     <td>{{ $subscription->id }}</td>
                     <td>
                       <a href="{{ url($subscription->user()->username) }}" target="_blank">
                         <img src="{{Storage::url(config('path.avatar').$subscription->user()->avatar)}}" width="40" height="40" class="rounded-circle me-1" /> {{ $subscription->user()->username }}
                       </a>
                     </td>

                     <td>{{ $subscription->plan->name }}</td>
										 <td>{{ $subscription->interval == 'month' ? __('misc.monthly') : __('misc.yearly') }}</td>
										 <td>{{ Helper::formatDate($subscription->created_at) }}</td>
										 <td>
											 @if ($subscription->ends_at)
 							          {{ Helper::formatDate($subscription->ends_at) }}
 							        @else
 							          {{ Helper::formatDate($subscription->user()->subscription('main', $subscription->stripe_price)->asStripeSubscription()->current_period_end, true) }}
 							        @endif
										 </td>

										 <td>
											 @if ($subscription->stripe_id == ''
												 && strtotime($subscription->ends_at) > strtotime(now()->format('Y-m-d H:i:s'))
												 && $subscription->cancelled == 'no'
													 || $subscription->stripe_id != '' && $subscription->stripe_status == 'active'
												 )
												 <span class="badge bg-success">{{trans('misc.active')}}</span>
											 @elseif ($subscription->stripe_id != '' && $subscription->stripe_status == 'incomplete')
												 <span class="badge bg-warning">{{trans('misc.pending')}}</span>
											 @else
												 <span class="badge bg-danger">{{ $subscription->cancelled == 'no' && $subscription->stripe_status != 'canceled' ? __('misc.expired') : __('misc.cancelled') }}</span>
											 @endif
										 </td>

                   </tr><!-- /.TR -->
                   @endforeach

									@else
										<h5 class="text-center p-5 text-muted fw-light m-0">{{ trans('misc.no_results_found') }}</h5>
									@endif

								</tbody>
								</table>
							</div><!-- /.box-body -->

				 </div><!-- card-body -->
 			</div><!-- card  -->

			{{ $subscriptions->onEachSide(0)->links() }}
 		</div><!-- col-lg-12 -->

	</div><!-- end row -->
</div><!-- end content -->
@endsection
