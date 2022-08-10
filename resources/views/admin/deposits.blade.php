@extends('admin.layout')

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ __('misc.deposits') }} ({{$data->total()}})</span>
  </h5>

<div class="content">
	<div class="row">

		<div class="col-lg-12">

			@if (session('success_message'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
              <i class="bi bi-check2 me-1"></i>	{{ session('success_message') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                  <i class="bi bi-x-lg"></i>
                </button>
                </div>
              @endif

			<div class="card shadow-custom border-0">
				<div class="card-body p-lg-4">

					<div class="table-responsive p-0">
						<table class="table table-hover">
						 <tbody>

               @if ($data->total() !=  0 && $data->count() != 0)
                  <tr>
                     <th class="active">ID</th>
                     <th class="active">{{ trans('admin.user') }}</th>
                     <th class="active">{{ trans('misc.transaction_id') }}</th>
                     <th class="active">{{ trans('misc.amount') }}</th>
                     <th class="active">{{ trans('misc.payment_gateway') }}</th>
                     <th class="active">{{ trans('admin.date') }}</th>
                   </tr><!-- /.TR -->


                 @foreach ($data as $deposit)

                   <tr>
                     <td>{{ $deposit->id }}</td>
                     <td><a href="{{url($deposit->user()->username)}}" target="_blank">{{$deposit->user()->username}} <i class="bi-box-arrow-up-right"></i></a></td>
                     <td>{{ $deposit->txn_id }}</td>
                     <td>{{ Helper::amountFormat($deposit->amount) }}</td>
                     <td>{{ $deposit->payment_gateway }}</td>
                     <td>{{ date('d M, Y', strtotime($deposit->date)) }}</td>
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

			{{ $data->onEachSide(0)->links() }}
 		</div><!-- col-lg-12 -->

	</div><!-- end row -->
</div><!-- end content -->
@endsection
