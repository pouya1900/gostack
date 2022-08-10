@extends('layouts.app')

@section('title') {{trans('misc.referrals')}} -@endsection

@section('content')
<section class="section section-sm">
    <div class="container">
      <div class="row justify-content-center text-center mb-sm">
        <div class="col-lg-8 py-5">
          <h2 class="mb-0 font-montserrat"><i class="bi bi-person-plus me-2"></i> {{trans('misc.referrals')}}</h2>

          @if ($settings->referral_system == 'on')

            <p class="lead text-muted mt-0">
              {{trans('misc.referrals_welcome_desc', ['percentage' => $settings->percentage_referred])}}
              <small class="d-block">
                @if ($settings->referral_transaction_limit <> 'unlimited')
                  * {{ trans_choice('misc.total_transactions_per_referral', $settings->referral_transaction_limit, ['percentage' => $settings->percentage_referred, 'total' => $settings->referral_transaction_limit]) }}
                @else
                  * {{trans('misc.total_transactions_referral_unlimited', ['percentage' => $settings->percentage_referred])}}
                @endif

              </small>
            </p>

            <button class="d-none copy-url" id="copyLink" data-clipboard-text="{{ url('/?ref='.auth()->id()) }}"></button>
            <span>
              <span class="text-muted">{{ trans('misc.your_referral_link') }}</span>

              <span class="text-break"><strong>{{ url('/?ref='.auth()->id()) }}</strong></span>

              <button class="btn btn-link e-none p-1 text-decoration-none showTooltip" title="{{trans('misc.copy_link')}}" onclick="$('#copyLink').trigger('click')">
  							<i class="far fa-clone"></i>
  						</button>
            </span>
          @else
          <div class="alert alert-danger mt-3">
          <span class="alert-inner--text">
            <i class="fa fa-exclamation-triangle me-1"></i> {{ trans('misc.referral_system_disabled') }}
          </span>
        </div>
          @endif

        </div>
      </div>
      <div class="row">

        <div class="col-lg-12 mb-5 mb-lg-0">

          <div class="content">
            <div class="row">
              <div class="col-lg-4 mb-2">
                <div class="card">
                  <div class="card-body">
                    <h4><i class="fas fa-users me-2 text-primary icon-dashboard"></i> {{ number_format(auth()->user()->referrals()->count()) }}</h4>
                    <small>{{ trans('misc.total_registered_users') }}</small>
                  </div>
                </div><!-- card 1 -->
              </div><!-- col-lg-4 -->

              <div class="col-lg-4 mb-2">
                <div class="card">
                  <div class="card-body">
                    <h4><i class="fa fa-receipt me-2 text-primary icon-dashboard"></i> {{ number_format(auth()->user()->referralTransactions()->count()) }}</h4>
                    <small>{{ trans('misc.total_transactions') }}</small>
                  </div>
                </div><!-- card 1 -->
              </div><!-- col-lg-4 -->

              <div class="col-lg-4 mb-2">
                <div class="card">
                  <div class="card-body">
                    <h4><i class="fas fa-hand-holding-usd me-2 text-primary icon-dashboard"></i> {{ Helper::amountFormatDecimal(auth()->user()->referralTransactions()->sum('earnings')) }}</h4>
                    <small>{{ trans('misc.earnings_total') }}</small>
                  </div>
                </div><!-- card 1 -->
              </div><!-- col-lg-4 -->

              <div class="col-lg-12 mt-3 py-4">
                 <div class="card">
                   <div class="card-body">
                     <h4 class="mb-4">{{ trans('misc.transactions') }}</h4>

                     <div class="table-responsive">
                       <table class="table table-striped m-0">
                         <thead>
                           <tr>
                             <th scope="col">{{trans('admin.type')}}</th>
                             <th scope="col">{{trans('admin.date')}}</th>
                             <th scope="col">{{trans('misc.earnings')}}</th>
                           </tr>
                         </thead>

                         <tbody>

                        @if ($transactions->count() != 0)
                           @foreach ($transactions as $referred)
                             <tr>
                               <td>{{ __('misc.purchase_'.$referred->type) }}</td>
                               <td>{{ Helper::formatDate($referred->created_at) }}</td>
                               <td>{{ Helper::amountFormatDecimal($referred->earnings) }}</td>
                             </tr>
                           @endforeach

                         @else
                           <tr>
                             <td>{{ trans('misc.no_transactions_yet') }}</td>
                             <td></td>
                             <td></td>
                           </tr>
                          @endif

                         </tbody>
                       </table>
                     </div>
                   </div>
                 </div><!-- card -->

                 @if ($transactions->hasPages())
         			    	{{ $transactions->links() }}
         			    	@endif

              </div><!-- col-lg-12 -->

            </div><!-- end row -->
          </div><!-- end content -->

        </div><!-- end col-md-6 -->

      </div>
    </div>
  </section>
@endsection
