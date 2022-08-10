@extends('layouts.app')

@section('title') {{ trans('misc.my_purchases') }} -@endsection

@section('content')
<section class="section section-sm">

    <div class="container py-5">

      <div class="row">

        <div class="col-md-3">
          @include('users.navbar-settings')
        </div>

        <div class="col-md-9 mb-5 mb-lg-0">

          @if (session('error'))
       <div class="alert alert-danger alert-dismissible fade show" role="alert">
                 {{ session('error') }}

                 <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                   <i class="bi bi-x-lg"></i>
                 </button>
                 </div>
         @endif

          <h5 class="d-inline-block mb-4">{{ trans('misc.my_purchases') }} ({{$data->total()}})</h5>

          @if ($data->count() != 0)
          <div class="card shadow-sm">
            <div class="table-responsive">
              <table class="table m-0">
                <thead>
                  <th class="active">ID</th>
                  <th class="active">{{ trans('misc.thumbnail') }}</th>
                  <th class="active">{{ trans('admin.title') }}</th>
                  <th class="active">{{ trans('admin.type') }}</th>
                  <th class="active">{{ trans('misc.license') }}</th>
                  <th class="active">{{ trans('misc.purchase_code') }}</th>
                  <th class="active">{{ trans('misc.price') }}</th>
                  <th class="active">{{ trans('admin.date') }}</th>
                  <th class="active">{{ trans('admin.actions') }}</th>
                </thead>

                <tbody>
                  @foreach ($data as $purchase)

                    @php

                    if(null !== $purchase->images()) {

                      $purchaseNull = false;
                      $image_photo = Storage::url(config('path.thumbnail').$purchase->images()->thumbnail);
                      $image_title = $purchase->images()->title;
                      $image_url   = url('photo', $purchase->images()->id);
                      $download_url = url('purchase/stock', $purchase->images()->token_id);


                    } else {
                      $purchaseNull = true;
                      $image_photo = url('public/img/placeholder.jpg');
                      $image_title = trans('misc.not_available');
                      $image_url   = 'javascript:void(0);';
                      $download_url = 'javascript:void(0);';

                    }

                    switch ($purchase->type) {
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

                      switch ($purchase->license) {
                			case 'regular':
                				$license = trans('misc.regular');
                				break;
                			case 'extended':
                				$license = trans('misc.extended');
                				break;
                        }

                    @endphp

                    <tr>
                      <td>{{ $purchase->id }}</td>
                      <td><img src="{{$image_photo}}" width="50" onerror="" /></td>
                      <td><a href="{{ $image_url }}" title="{{$image_title}}">{{ str_limit($image_title, 25, '...') }}</a></td>
                      <td>{{ $type }}</td>
                      <td>{{$license}}</td>
                      <td>{{$purchase->purchase_code}}</td>
                      <td>{{ Helper::amountFormat($purchase->price) }}</td>
                      <td>{{ date('d M, Y', strtotime($purchase->date)) }}</td>
                      <td>
                        @if ($purchaseNull)
                          <em>{{$image_title}}</em>
                        @else

                        <form method="POST" action="{{$download_url}}" accept-charset="UTF-8">
                          @csrf
                          <input name="downloadAgain" type="hidden" value="true">
                          <input name="type" type="hidden" value="{{$purchase->type}}">
                          <input name="license" type="hidden" value="{{$purchase->license}}">
                          <button type="submit" class="btn btn-success btn-sm d-block w-100">
                            <i class="bi bi-download"></i>
                          </button>
                        </form>

                        @if ($purchase->invoice())
                          <a class="btn btn-light border btn-sm d-block mt-1" title="{{trans('misc.invoice')}}" href="{{url('invoice', $purchase->invoice()->id)}}" target="_blank">
                            <i class="bi-receipt"></i>
                          </a>
                        @endif


                      @endif
                        </td>
                    </tr><!-- /.TR -->

                    @endforeach
                </tbody>
              </table>
            </div><!-- table-responsive -->
          </div><!-- card -->

          @if ($data->hasPages())
  			    	<div class="mt-3">
                {{ $data->links() }}
              </div>
  			    	@endif

            @else
            <h3 class="mt-0 fw-light">
              {{ trans('misc.no_results_found') }}
            </h3>

        @endif

        </div><!-- end col-md-6 -->
      </div>
    </div>
  </section>
@endsection
