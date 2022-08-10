<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="_token" content="{!! csrf_token() !!}"/>
    <title>{{trans('misc.invoice')}} #{{str_pad($data->id, 4, "0", STR_PAD_LEFT)}}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    @include('includes.css_general')

    <!-- AdminLTE Skins. Choose a skin from the css/skins
        folder instead of downloading all of them to reduce the load. -->
    <link rel="shortcut icon" href="{{ url('public/img', $settings->favicon) }}" />
  </head>

  <body class="bg-light">
    <div class="wrapper">
  <!-- Main content -->
  <section class="invoice p-4 bg-white">
    <!-- title row -->
    <div class="row">
      <div class="col-12">
        <h2 class="border-bottom pb-3">
          <img src="{{ url('public/img', $settings->logo)}}" width="110">
          <small class="float-end date-invoice mt-3">{{trans('admin.date')}}: {{Helper::formatDate($data->created_at)}}</small>
        </h2>
      </div>
      <!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info mb-3">
      <div class="col-sm-4 invoice-col">
        {{trans('misc.from')}}
        <address>
          @if ($settings->company)
            <span class="w-100 d-block mb-1 fw-bold">{{$settings->company}}</span>
          @endif

          @if ($settings->address)
            <span class="w-100 d-block mb-1">{{$settings->address}}</span>
          @endif

          @if ($settings->city || $settings->zip)
            <span class="w-100 d-block mb-1">{{$settings->city}} {{$settings->zip}}</span>
          @endif

          @if ($settings->country)
            <span class="w-100 d-block mb-1">{{$settings->country}}</span>
          @endif

          <span class="w-100 d-block mb-1">{{trans('auth.email')}}: {{$settings->email_admin}}</span>

          @if ($settings->vat)
            {{trans('misc.vat')}}: {{$settings->vat}}
          @endif
        </address>
      </div>
      <!-- /.col -->
      <div class="col-sm-4 invoice-col">
        {{trans('misc.to')}}
        <address>
          <span class="w-100 d-block mb-1 fw-bold">{{$data->user()->name}} {{$data->user()->company != '' ? '- '.$data->user()->company : null }}</span>

          @if ($data->user()->address)
            <span class="w-100 d-block mb-1">{{$data->user()->address}}</span>
          @endif

          @if ($data->user()->city || $data->user()->zip)
            <span class="w-100 d-block mb-1">{{$data->user()->city}}, {{$data->user()->zip}}</span>
          @endif

          @if (isset($data->user()->country()->country_name))
            <span class="w-100 d-block mb-1">{{$data->user()->country()->country_name}}</span>
          @endif

          {{trans('auth.email')}}: {{$data->user()->email}}
        </address>
      </div>
      <!-- /.col -->
      <div class="col-sm-4 invoice-col">
        <b>{{trans('misc.invoice')}} #{{str_pad($data->id, 4, "0", STR_PAD_LEFT)}}</b><br>
        <b>{{trans('misc.payment_due')}}</b> {{Helper::formatDate($data->created_at)}}<br>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    <!-- Table row -->
    <div class="row">
      <div class="col-12 table-responsive">
        <table class="table table-borderless table-striped">
          <thead>
          <tr>
            <th>{{trans('misc.qty')}}</th>
            <th class="text-center">{{trans('misc.description')}}</th>
            <th class="text-end">{{trans('misc.subtotal')}}</th>
          </tr>
          </thead>
          <tbody>
          <tr>
            <td>1</td>

            @if ($data->deposits_id)
              <td class="text-center">{{trans('misc.add_funds')}}</td>
            @endif

            @if ($data->purchases_id)

              @php
              switch ($data->purchase()->type) {
              case 'small':
                $type = trans('misc.small_photo');
                break;
              case 'medium':
                $type = trans('misc.medium_photo');
                break;
              case 'large':
                $type = trans('misc.large_photo');
                break;
              case 'vector':
                  $type = trans('misc.vector_graphic');
                  break;
                }

                switch ($data->purchase()->license) {
                case 'regular':
                  $license = trans('misc.license_regular');
                  break;
                case 'extended':
                  $license = trans('misc.license_extended');
                  break;
                  }
              @endphp

              <td class="text-center">{{trans('misc.stock')}} {{ $type }} - {{ $license }}</td>
            @endif

            @if ($data->subscriptions_id)
              <td class="text-center">{{ __('misc.subscription_to_plan', ['plan' => $data->subscription()->plan->name]) }} - {{ $data->subscription()->interval == 'month' ? __('misc.billed_monthly') : __('misc.billed_yearly') }} </td>
            @endif

            <td class="text-end">{{Helper::amountFormatDecimal($amount)}} {{ $settings->currency_code }}</td>
          </tr>
          </tbody>
        </table>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row">
      <!-- /.col -->
      <div class="col-4 col-lg-6"></div>
      <!-- /.col -->
      <div class="col-8 col-lg-6">
        <div class="table-responsive">
          <table class="table">
            <tr class="border-bottom">
              <th class="w-50 text-end">{{trans('misc.subtotal')}}:</th>
              <td class="text-end">{{Helper::amountFormatDecimal($amount)}} {{ $settings->currency_code }}</td>
            </tr>

            @if ($transactionFee)
              <tr class="border-bottom">
                <th class="w-50 text-end">{{trans('misc.handling_fee')}}: {{ $percentageApplied }}</th>
                <td class="text-end">{{Helper::amountFormatDecimal($transactionFee)}} {{ $settings->currency_code }}</td>
              </tr>
            @endif

              @foreach($taxes as $tax)
                <tr class="border-bottom">
                  <th class="w-50 text-end">{{ $tax->name }} {{ $tax->percentage }}%:</th>
                  <td class="text-end">{{Helper::amountFormatDecimal(Helper::calculatePercentage($data->amount, $tax->percentage))}} {{ $settings->currency_code }}</td>
                </tr>
              @endforeach

            <tr class="h5 text-end">
              <th class="text-end">{{trans('misc.total')}}:</th>
              <td><strong>{{Helper::amountFormatDecimal($totalAmount)}} {{ $settings->currency_code }}</strong></td>
            </tr>
          </table>
        </div>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
    <div class="row no-print">
        <div class="col-12">
          <a href="javascript:void(0);" onclick="window.print();" class="btn btn-light border"><i class="fa fa-print"></i> {{trans('misc.print')}}</a>
        </div>
      </div>
  </section>
  <!-- /.content -->
</div>
<!-- ./wrapper -->
  </body>
</html>
