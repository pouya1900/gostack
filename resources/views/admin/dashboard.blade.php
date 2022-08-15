@extends('admin.layout')

@section('content')
	<h4 class="mb-4 fw-light">{{ __('admin.dashboard') }} <small class="fs-6">v{{$settings->version}}</small></h4>

<div class="content">
	<div class="row">

		<div class="col-lg-3 mb-3">
			<div class="card shadow-custom border-0 overflow-hidden">
				<div class="card-body">
					<h3>
						<i class="fa fa-shopping-cart me-2 icon-dashboard"></i>
						<span>{{ number_format($totalSales) }}</span>
					</h3>
					<small>{{ trans('misc.total_sales') }}</small>

					<span class="icon-wrap icon--admin"><i class="bi bi-cart2"></i></span>
				</div>
			</div><!-- card 1 -->
		</div><!-- col-lg-3 -->

		<div class="col-lg-3 mb-3">
			<div class="card shadow-custom border-0 overflow-hidden">
				<div class="card-body">
					<h3><i class="fas fa-hand-holding-usd me-2 icon-dashboard"></i> {{ Helper::amountFormatDecimal($earningNetAdmin) }}</h3>
					<small>{{ trans('misc.total_earnings') }}</small>

					<span class="icon-wrap icon--admin"><i class="bi bi-cash-stack"></i></span>
				</div>
			</div><!-- card 1 -->
		</div><!-- col-lg-3 -->

		<div class="col-lg-3 mb-3">
			<div class="card shadow-custom border-0 overflow-hidden">
				<div class="card-body">
					<h3><i class="fa fa-users me-2 icon-dashboard"></i> {{ $totalUsers }}</h3>
					<small>{{ trans('admin.members') }}</small>
					<span class="icon-wrap icon--admin"><i class="bi bi-people"></i></span>
				</div>
			</div><!-- card 1 -->
		</div><!-- col-lg-3 -->

		<div class="col-lg-3 mb-3">
			<div class="card shadow-custom border-0 overflow-hidden">
				<div class="card-body">
					<h3><i class="fa fa-images me-2 icon-dashboard"></i> {{ number_format($totalImages) }}</h3>
					<small>{{ trans('misc.images') }}</small>
					<span class="icon-wrap icon--admin"><i class="far fa-images"></i></span>
				</div>
			</div><!-- card 1 -->
		</div><!-- col-lg-3 -->



		<div class="col-lg-4 mb-3">
			<div class="card shadow-custom border-0 overflow-hidden">
				<div class="card-body">
					<h6 class="{{$stat_revenue_today > 0 ? 'text-success' : 'text-danger' }}">
						{{ Helper::amountFormatDecimal($stat_revenue_today) }}

							{!! Helper::PercentageIncreaseDecrease($stat_revenue_today, $stat_revenue_yesterday) !!}
					</h6>
					<small>{{ trans('misc.revenue_today') }}</small>
					<span class="icon-wrap icon--admin"><i class="bi bi-graph-up-arrow"></i></span>
				</div>
			</div><!-- card 1 -->
		</div><!-- col-lg-4 -->

		<div class="col-lg-4 mb-3">
			<div class="card shadow-custom border-0 overflow-hidden">
				<div class="card-body">
					<h6 class="{{$stat_revenue_week > 0 ? 'text-success' : 'text-danger' }}">
						{{ Helper::amountFormatDecimal($stat_revenue_week) }}

							{!! Helper::PercentageIncreaseDecrease($stat_revenue_week, $stat_revenue_last_week) !!}
					</h6>
					<small>{{ trans('misc.revenue_week') }}</small>
					<span class="icon-wrap icon--admin"><i class="bi bi-graph-up"></i></span>
				</div>
			</div><!-- card 1 -->
		</div><!-- col-lg-4 -->

		<div class="col-lg-4 mb-3">
			<div class="card shadow-custom border-0 overflow-hidden">
				<div class="card-body">
					<h6 class="{{$stat_revenue_month > 0 ? 'text-success' : 'text-danger' }}">
						{{ Helper::amountFormatDecimal($stat_revenue_month) }}

							{!! Helper::PercentageIncreaseDecrease($stat_revenue_month, $stat_revenue_last_month) !!}
					</h6>
					<small>{{ trans('misc.revenue_month') }}</small>
					<span class="icon-wrap icon--admin"><i class="bi bi-graph-up-arrow"></i></span>
				</div>
			</div><!-- card 1 -->
		</div><!-- col-lg-4 -->

		<div class="col-lg-6 mt-3 py-4">
			 <div class="card shadow-custom border-0">
				 <div class="card-body">
					 <h6 class="mb-4 fw-light">{{ trans('misc.earnings_raised_last') }}</h6>
					 <div style="height: 350px">
						<canvas id="Chart"></canvas>
					</div>
				 </div>
			 </div>
		</div>

		<div class="col-lg-6 mt-0 mt-lg-3 py-4">
			 <div class="card shadow-custom border-0">
				 <div class="card-body">
					 <h6 class="mb-4 fw-light">{{ trans('misc.sales_last_30_days') }}</h6>
					 <div style="height: 350px">
						<canvas id="ChartSales"></canvas>
					</div>
				 </div>
			 </div>
		</div>

		<div class="col-lg-6 mt-0 mt-lg-3 py-4">
			 <div class="card shadow-custom border-0">
				 <div class="card-body">
					 <h6 class="mb-4 fw-light">{{ trans('admin.latest_members') }}</h6>

					 @foreach (User::orderBy('id','DESC')->take(5)->get() as $user)
						 <div class="d-flex mb-3">
							  <div class="flex-shrink-0">
							    <img src="{{ Storage::disk('default')->url(config('path.avatar').$user->avatar) }}" width="50" class="rounded-circle" />
							  </div>
							  <div class="flex-grow-1 ms-3">
							    <h6 class="m-0 fw-light text-break">
										<a href="{{ url($user->username) }}" target="_blank">
											{{ $user->name ?: $user->username }}
											</a>
											<small class="float-end badge rounded-pill bg-{{ $user->status == 'active' ? 'success' : ($user->status == 'pending' ? 'info' : 'warning') }}">
												{{ $user->status == 'active' ? trans('misc.active') : ($user->status == 'pending' ? trans('misc.pending') : trans('admin.suspended')) }}
											</small>
									</h6>
									<div class="w-100 small">
										{{ '@'.$user->username }} / {{ Helper::formatDate($user->date) }}
									</div>
							  </div>
							</div>
					 @endforeach

					 @if ($totalUsers == 0)
						 <small>{{ trans('admin.no_result') }}</small>
					 @endif
				 </div>

				 @if ($totalUsers != 0)
				 <div class="card-footer bg-light border-0 p-3">
					   <a href="{{ url('panel/admin/members') }}" class="text-muted font-weight-medium d-flex align-items-center justify-content-center arrow">
							 {{ trans('admin.view_all_members') }}
						 </a>
					 </div>
				 @endif

			 </div>
		</div>

		<div class="col-lg-6 mt-0 mt-lg-3 py-4">
			 <div class="card shadow-custom border-0">
				 <div class="card-body">
					 <h6 class="mb-4 fw-light">{{ trans('admin.latest_images') }}</h6>

					 @foreach (Images::orderBy('id','DESC')->take(5)->get() as $image)
						 <div class="d-flex mb-3">
							  <div class="flex-shrink-0">
							    <img src="{{ url('files/preview/'.$image->stock->first()->resolution, $image->thumbnail).'?type=thumbnail' }}" width="50" class="rounded" />
							  </div>
							  <div class="flex-grow-1 ms-3">
							    <h6 class="m-0 fw-light text-break">
										<a href="{{ url('photo', $image->id) }}" target="_blank">
											{{ $image->title }}
											</a>
											<small class="float-end badge rounded-pill bg-{{ $image->status == 'active' ? 'success' : 'warning' }}">
												{{ $image->status == 'active' ? trans('misc.active') : trans('misc.pending') }}
											</small>
									</h6>
									<div class="w-100 small">
										{{ trans('misc.by') }} {{ '@'.$image->user()->username }} / {{ Helper::formatDate($image->date) }}
									</div>
							  </div>
							</div>
					 @endforeach

					 @if ($totalImages == 0)
						 <small>{{ trans('admin.no_result') }}</small>
					 @endif
				 </div>

				 @if ($totalImages != 0)
				 <div class="card-footer bg-light border-0 p-3">
					   <a href="{{ url('panel/admin/images') }}" class="text-muted font-weight-medium d-flex align-items-center justify-content-center arrow">
							 {{ trans('admin.view_all_images') }}
						 </a>
					 </div>
					  @endif
			 </div>
		</div>

	</div><!-- end row -->
</div><!-- end content -->
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
