@extends('layouts.app')

@section('title') {{ trans('misc.withdrawals') }} - @endsection

@section('content')
<section class="section section-sm">

<div class="container-custom container py-5">
  <div class="row">

    <div class="col-md-3">
      @include('users.navbar-settings')
    </div>

		<!-- Col MD -->
		<div class="col-md-9">

			@if (session('success_message'))
			<div class="alert alert-success alert-dismissible fade show" role="alert">
            		<i class="bi bi-check2 me-1"></i>	{{ session('success_message') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                  <i class="bi bi-x-lg"></i>
                </button>
            		</div>
            	@endif

            	 @if (session('error'))
			<div class="alert alert-danger alert-dismissible fade show" role="alert">
            		{{ session('error') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                  <i class="bi bi-x-lg"></i>
                </button>
            		</div>
            	@endif

			@include('errors.errors-forms')

      <h5 class="mb-4">{{ trans('misc.withdrawals') }}</h5>

      <div class="alert alert-success overflow-hidden" role="alert">
         <div class="inner-wrap">
            <h4>
               <small>{{trans('misc.balance')}}</small> {{Helper::amountFormatDecimal(Auth::user()->balance)}} <small>{{$settings->currency_code}}</small>
            </h4>
            <i class="fa fa-info-circle m-1"></i>
            <span>{{trans('misc.amount_min_withdrawal')}} <strong>{{Helper::amountFormat($settings->amount_min_withdrawal)}} {{$settings->currency_code}}</strong>
            </span>
         </div>
         <span class="icon-wrap"><i class="bi bi-arrow-left-right"></i></span>
      </div>

      @if (auth()->user()->balance >= $settings->amount_min_withdrawal)
        <form method="POST" action="{{ url('request/withdrawal') }}" accept-charset="UTF-8">
          @csrf
          <button type="submit" class="btn btn-lg w-100 btn-custom"><i class="bi bi-arrow-left-right me-2"></i> {{trans('misc.withdraw_balance')}}</button>
        </form>
          @else
            <button disabled class="btn btn-lg w-100 btn-custom">{{trans('misc.withdraw_balance')}}</button>
          @endif

          @if ($withdrawals->count() != 0)
          <div class="table-responsive mt-5 border">
            <table class="table m-0">
              <thead>
                <tr>
                  <th scope="col">{{trans('admin.amount')}}</th>
                  <th scope="col">{{trans('misc.method')}}</th>
                  <th scope="col">{{trans('admin.date')}}</th>
                  <th scope="col">{{trans('admin.status')}}</th>
                  <th scope="col">{{trans('admin.actions')}}</th>
                </tr>
              </thead>

              <tbody>

              @foreach ($withdrawals as $withdrawal)
                  <tr>
                    <td>{{Helper::amountFormatDecimal($withdrawal->amount)}}</td>
                    <td>{{ $withdrawal->gateway == 'Bank' ? trans('misc.bank_transfer') : $withdrawal->gateway }}</td>
                    <td>{{Helper::formatDate($withdrawal->date)}}</td>
                    <td>@if ( $withdrawal->status == 'paid' )
                    <span class="badge badge-pill bg-success text-uppercase">{{trans('misc.paid')}}</span>
                    @else
                    <span class="badge badge-pill bg-warning text-uppercase">{{trans('misc.pending_to_pay')}}</span>
                    @endif
                  </td>
                    <td>

                      @if ( $withdrawal->status != 'paid' )
                      {!! Form::open([
                        'method' => 'POST',
                        'url' => "delete/withdrawal/$withdrawal->id",
                        'class' => 'd-inline'
                      ]) !!}

                      {!! Form::button(trans('misc.delete'), ['class' => 'btn btn-danger btn-sm deleteW p-1 px-2']) !!}
                      {!! Form::close() !!}

                  @else

                   - {{trans('misc.paid')}} -

                  @endif
                  </td>
                </tr>
                @endforeach

              </tbody>
            </table>
          </div>

          @if ($withdrawals->hasPages())
            {{ $withdrawals->links() }}
          @endif

        @endif

		</div><!-- /COL MD -->
  </div><!-- row -->
 </div><!-- container -->
</section>
 <!-- container wrap-ui -->
@endsection

@section('javascript')
<script type="text/javascript">

$(".deleteW").click(function(e) {
   	e.preventDefault();

   	var element = $(this);
    var form    = $(element).parents('form');
    element.blur();

	swal({
    title: "{{trans('misc.delete_confirm')}}",
		 text: "{{trans('misc.confirm_delete_withdrawal')}}",
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
		    	 	form.submit();
		    	 	}
		   });// End swal


		 });
</script>
@endsection
