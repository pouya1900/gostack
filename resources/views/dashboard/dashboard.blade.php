@extends('layouts.app')

@section('title') {{ trans('admin.dashboard') }} - @endsection

@section('content')
<section class="section section-sm">

<div class="container py-5">
  <div class="row">

    <div class="col-md-3">
      @include('users.navbar-settings')
    </div>

		<!-- Col MD -->
		<div class="col-md-9">

      <h5 class="mb-4">{{ trans('admin.dashboard') }}</h5>

			<div class="content">
				<div class="row">
					<div class="col-lg-4 mb-2">
						<div class="card shadow-sm overflow-hidden">
							<div class="card-body">
								<h5><i class="fas fa-hand-holding-usd me-2 icon-dashboard"></i> {{ Helper::amountFormatDecimal($earningNetUser) }}</h5>
								<small>{{ trans('misc.total_earnings') }}</small>
                <span class="icon-wrap opacity-25">
                  <i class="bi-cash-stack"></i>
                </span>
							</div>
						</div><!-- card 1 -->
					</div><!-- col-lg-4 -->

					<div class="col-lg-4 mb-2">
						<div class="card shadow-sm overflow-hidden">
							<div class="card-body">
								<h5><i class="fas fa-wallet me-2 icon-dashboard"></i> {{ Helper::amountFormatDecimal(auth()->user()->balance) }}</h5>
								<small>{{ trans('misc.balance') }}
									@if (auth()->user()->balance >= $settings->amount_min_withdrawal)
									<a href="{{ url('user/dashboard/withdrawals')}}" class="text-decoration-underline"> {{ trans('misc.withdraw_balance') }}</a>
								@endif
								</small>
                <span class="icon-wrap opacity-25">
                  <i class="bi-wallet2"></i>
                </span>
							</div>
						</div><!-- card 1 -->
					</div><!-- col-lg-4 -->

					<div class="col-lg-4 mb-2">
						<div class="card shadow-sm overflow-hidden">
							<div class="card-body">
								<h5>
									<i class="fa fa-shopping-cart me-2 icon-dashboard"></i>
									<span>{{ number_format($totalSales) }}</span>
								</h5>
								<small>{{ trans('misc.total_sales') }}</small>
                <span class="icon-wrap opacity-25">
                  <i class="bi-cart"></i>
                </span>
							</div>
						</div><!-- card 1 -->
					</div><!-- col-lg-4 -->

					<div class="col-lg-4 mb-2">
						<div class="card shadow-sm overflow-hidden">
							<div class="card-body">
								<h6 class="{{$stat_revenue_today > 0 ? 'text-success' : 'text-danger' }}">
									{{ Helper::amountFormatDecimal($stat_revenue_today) }}

										{!! Helper::PercentageIncreaseDecrease($stat_revenue_today, $stat_revenue_yesterday) !!}
								</h6>
								<small>{{ trans('misc.revenue_today') }}</small>
                <span class="icon-wrap opacity-25">
                  <i class="bi-graph-up-arrow"></i>
                </span>
							</div>
						</div><!-- card 1 -->
					</div><!-- col-lg-4 -->

					<div class="col-lg-4 mb-2">
						<div class="card shadow-sm overflow-hidden">
							<div class="card-body">
								<h6 class="{{$stat_revenue_week > 0 ? 'text-success' : 'text-danger' }}">
									{{ Helper::amountFormatDecimal($stat_revenue_week) }}

										{!! Helper::PercentageIncreaseDecrease($stat_revenue_week, $stat_revenue_last_week) !!}
								</h6>
								<small>{{ trans('misc.revenue_week') }}</small>
                <span class="icon-wrap opacity-25">
                  <i class="bi-graph-up-arrow"></i>
                </span>
							</div>
						</div><!-- card 1 -->
					</div><!-- col-lg-4 -->

					<div class="col-lg-4 mb-2">
						<div class="card shadow-sm overflow-hidden">
							<div class="card-body">
								<h6 class="{{$stat_revenue_month > 0 ? 'text-success' : 'text-danger' }}">
									{{ Helper::amountFormatDecimal($stat_revenue_month) }}

										{!! Helper::PercentageIncreaseDecrease($stat_revenue_month, $stat_revenue_last_month) !!}
								</h6>
								<small>{{ trans('misc.revenue_month') }}</small>
                <span class="icon-wrap opacity-25">
                  <i class="bi-graph-up-arrow"></i>
                </span>
							</div>
						</div><!-- card 1 -->
					</div><!-- col-lg-4 -->

					<div class="col-lg-4 mb-2">
						<div class="card shadow-sm">
							<div class="card-body">
								<h5>
									<i class="bi bi-images me-2"></i> {{ number_format($totalImages) }}
								</h5>
								<small>{{ trans_choice('misc.photos_plural', $totalImages) }}</small>
							</div>
						</div><!-- card 1 -->
					</div><!-- col-lg-4 -->

					<div class="col-lg-4 mb-2">
						<div class="card shadow-sm">
							<div class="card-body">
								<h5>
									<i class="bi bi-exclamation-triangle me-2"></i> {{ number_format($photosPending) }}

									@if ($photosPending != 0)
										<small class="float-end h6">
											<a class="arrow" href="{{ url('photos/pending') }}">{{ trans('misc.view') }}</a>
										</small>
									@endif
								</h5>
								<small>{{ trans('misc.photos_pending') }}</small>
							</div>
						</div><!-- card 1 -->
					</div><!-- col-lg-4 -->

					<div class="col-lg-4 mb-2">
						<div class="card shadow-sm">
							<div class="card-body">
								<h5>
									<i class="bi bi-download me-2"></i> {{ number_format(auth()->user()->totalDownloads()->count()) }}

								</h5>
								<small>{{ trans('misc.downloads') }}</small>
							</div>
						</div><!-- card 1 -->
					</div><!-- col-lg-4 -->

					<div class="col-lg-12 mt-3 py-4">
						 <div class="card shadow-sm">
							 <div class="card-body">
								 <h5 class="mb-4">{{ trans('misc.earnings_raised_last') }}</h5>
								 <div style="height: 350px">
									<canvas id="Chart"></canvas>
								</div>
							 </div>
						 </div>
					</div>

					<div class="col-lg-12 mt-3 py-4">
						 <div class="card shadow-sm">
							 <div class="card-body">
								 <h5 class="mb-4">{{ trans('misc.sales_last_30_days') }}</h5>
								 <div style="height: 350px">
									<canvas id="ChartSales"></canvas>
								</div>
							 </div>
						 </div>
					</div>

				</div><!-- end row -->
			</div><!-- end content -->

		</div><!-- /COL MD -->
  </div><!-- row -->
 </div><!-- container -->
</section>
 <!-- container wrap-ui -->
@endsection

@section('javascript')
  <script src="{{ asset('public/js/Chart.min.js') }}"></script>

  <script type="text/javascript">

function decimalFormat(nStr)
{
  @if ($settings->decimal_format == 'dot')
	 $decimalDot = '.';
	 $decimalComma = ',';
	 @else
	 $decimalDot = ',';
	 $decimalComma = '.';
	 @endif

   @if ($settings->currency_position == 'left')
   currency_symbol_left = '{{$settings->currency_symbol}}';
   currency_symbol_right = '';
   @else
   currency_symbol_right = '{{$settings->currency_symbol}}';
   currency_symbol_left = '';
   @endif

    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? $decimalDot + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + $decimalComma + '$2');
    }
    return currency_symbol_left + x1 + x2 + currency_symbol_right;
  }

  function transparentize(color, opacity) {
			var alpha = opacity === undefined ? 0.5 : 1 - opacity;
			return Color(color).alpha(alpha).rgbString();
		}

  var init = document.getElementById("Chart").getContext('2d');

  const gradient = init.createLinearGradient(0, 0, 0, 300);
                    gradient.addColorStop(0, '#268707');
                    gradient.addColorStop(1, '#2687072e');

  const lineOptions = {
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        hitRadius: 5,
                        pointHoverBorderWidth: 3
                    }

  var ChartArea = new Chart(init, {
      type: 'line',
      data: {
          labels: [{!!$label!!}],
          datasets: [{
              label: '{{trans('misc.earnings')}}',
              backgroundColor: gradient,
              borderColor: '#268707',
              data: [{!!$data!!}],
              borderWidth: 2,
              fill: true,
              lineTension: 0.4,
              ...lineOptions
          }]
      },
      options: {
          scales: {
              yAxes: [{
                  ticks: {
                    min: 0, // it is for ignoring negative step.
                     display: true,
                      maxTicksLimit: 8,
                      padding: 10,
                      beginAtZero: true,
                      callback: function(value, index, values) {
                          return '@if($settings->currency_position == 'left'){{ $settings->currency_symbol }}@endif' + value + '@if($settings->currency_position == 'right'){{ $settings->currency_symbol }}@endif';
                      }
                  }
              }],
              xAxes: [{
                gridLines: {
                  display:false
                },
                display: true,
                ticks: {
                  maxTicksLimit: 15,
                  padding: 5,
                }
              }]
          },
          tooltips: {
            mode: 'index',
            intersect: false,
            reverse: true,
            backgroundColor: '#000',
            xPadding: 16,
            yPadding: 16,
            cornerRadius: 4,
            caretSize: 7,
              callbacks: {
                  label: function(t, d) {
                      var xLabel = d.datasets[t.datasetIndex].label;
                      var yLabel = t.yLabel == 0 ? decimalFormat(t.yLabel) : decimalFormat(t.yLabel.toFixed(2));
                      return xLabel + ': ' + yLabel;
                  }
              },
          },
          hover: {
            mode: 'index',
            intersect: false
          },
          legend: {
              display: false
          },
          responsive: true,
          maintainAspectRatio: false
      }
  });

	// Sales last 30 days
	var sales = document.getElementById("ChartSales").getContext('2d');

  const gradientSales = sales.createLinearGradient(0, 0, 0, 300);
                    gradientSales.addColorStop(0, '#268707');
                    gradientSales.addColorStop(1, '#2687072e');

  const lineOptionsSales = {
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        hitRadius: 5,
                        pointHoverBorderWidth: 3
                    }

  var ChartArea = new Chart(sales, {
      type: 'bar',
      data: {
          labels: [{!!$label!!}],
          datasets: [{
              label: '{{trans('misc.sales')}}',
              backgroundColor: '#268707',
              borderColor: '#268707',
              data: [{!!$datalastSales!!}],
              borderWidth: 2,
              fill: true,
              lineTension: 0.4,
              ...lineOptionsSales
          }]
      },
      options: {
          scales: {
              yAxes: [{
                  ticks: {
                    min: 0, // it is for ignoring negative step.
                     display: true,
                      maxTicksLimit: 8,
                      padding: 10,
                      beginAtZero: true,
                      callback: function(value, index, values) {
                          return value;
                      }
                  }
              }],
              xAxes: [{
                gridLines: {
                  display:false
                },
                display: true,
                ticks: {
                  maxTicksLimit: 15,
                  padding: 5,
                }
              }]
          },
          tooltips: {
            mode: 'index',
            intersect: false,
            reverse: true,
            backgroundColor: '#000',
            xPadding: 16,
            yPadding: 16,
            cornerRadius: 4,
            caretSize: 7,
              callbacks: {
                  label: function(t, d) {
                      var xLabel = d.datasets[t.datasetIndex].label;
                      var yLabel = t.yLabel;
                      return xLabel + ': ' + yLabel;
                  }
              },
          },
          hover: {
            mode: 'index',
            intersect: false
          },
          legend: {
              display: false
          },
          responsive: true,
          maintainAspectRatio: false
      }
  });
  </script>
  @endsection
