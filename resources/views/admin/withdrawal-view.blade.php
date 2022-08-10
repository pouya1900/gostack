@extends('admin.layout')

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <a class="text-reset" href="{{ url('panel/admin/withdrawals') }}">{{ __('misc.withdrawals') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      #{{$data->id}}
  </h5>

<div class="content">
	<div class="row">

		<div class="col-lg-12">

			<div class="card shadow-custom border-0">
				<div class="card-body p-lg-5">

             <dl class="row">
              <dt class="col-sm-3 text-lg-end">{{ __('admin.user') }}</dt>
              <dd class="col-sm-9"><a href="{{url($data->user()->username)}}" target="_blank">{{ $data->user()->username }} <i class="bi-box-arrow-up-right"></i></a></dd>

              @if ($data->gateway == 'PayPal')
              <dt class="col-sm-3 text-lg-end">{{ __('admin.paypal_account') }}</dt>
              <dd class="col-sm-9">{{$data->account}}</dd>
            @else
              <dt class="col-sm-3 text-lg-end">{{ __('misc.bank_details') }}</dt>
              <dd class="col-sm-9">{!!Helper::checkText($data->account)!!}</dd>
            @endif

            <dt class="col-sm-3 text-lg-end">{{ __('misc.amount') }}</dt>
            <dd class="col-sm-9">{{Helper::amountFormatDecimal($data->amount) }}</dd>

            <dt class="col-sm-3 text-lg-end">{{ __('admin.date') }}</dt>
            <dd class="col-sm-9">{{date('d M, Y', strtotime($data->date))}}</dd>

            <dt class="col-sm-3 text-lg-end">{{ __('admin.status') }}</dt>
            <dd class="col-sm-9"><span class="badge bg-{{ $data->status == 'paid' ? 'success' : 'warning' }}">{{ $data->status == 'paid' ? __('misc.paid') : __('misc.pending_to_pay') }}</span></dd>

            @if ($data->status == 'paid')
            <dt class="col-sm-3 text-lg-end">{{ __('misc.date_paid') }}</dt>
            <dd class="col-sm-9">{{date('d M, Y', strtotime($data->date_paid))}}</dd>
          @endif

            </dl>

            @if ($data->status == 'pending')
            <form method="POST" action="{{ url('panel/admin/withdrawals/paid', $data->id) }}" enctype="multipart/form-data">
              @csrf
						<div class="row mb-3">
		          <div class="col-sm-10 offset-sm-2">
		            <button type="submit" class="btn btn-success mt-3 px-5">{{ __('misc.mark_paid') }}</button>
		          </div>
		        </div>
            </form>
          @endif

				 </div><!-- card-body -->
 			</div><!-- card  -->
 		</div><!-- col-lg-12 -->

	</div><!-- end row -->
</div><!-- end content -->
@endsection
