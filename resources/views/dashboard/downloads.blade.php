@extends('layouts.app')

@section('title') {{ trans('misc.downloads') }} -@endsection

@section('content')
<section class="section section-sm">

    <div class="container py-5">

      <div class="row">

        <div class="col-md-3">
          @include('users.navbar-settings')
        </div>

        <div class="col-md-9 mb-5 mb-lg-0">

          <h5 class="d-inline-block mb-4">{{ trans('misc.downloads') }} ({{$data->total()}})</h5>

          @if ($data->count() != 0)
          <div class="card shadow-sm">
            <div class="table-responsive">
              <table class="table m-0">
                <thead>
                  <th class="active">ID</th>
                  <th class="active">{{ trans('misc.thumbnail') }}</th>
                  <th class="active">{{ trans('admin.title') }}</th>
                  <th class="active">{{ trans('admin.type') }}</th>
                  <th class="active">{{ trans('admin.date') }}</th>
                  <th class="active">{{ trans('admin.actions') }}</th>
                </thead>

                <tbody>
                  @foreach ($data as $downloads)

                    @php

                    $image_photo = Storage::url(config('path.thumbnail').$downloads->thumbnail);
                    $image_title = $downloads->title;
                    $image_url   = url('photo', $downloads->id);

                    if ($downloads->type == 'subscription') {
                      $downloadUrl = url('subscription/stock', $downloads->token_id);
                    } else {
                      $downloadUrl = url('download/stock', $downloads->token_id);
                    }

                    switch ($downloads->size) {
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

                    @endphp

                    <tr>
                      <td>{{ $downloads->id }}</td>
                      <td><img src="{{$image_photo}}" width="50" onerror="" /></td>
                      <td><a href="{{ $image_url }}" title="{{$image_title}}" target="_blank">{{ str_limit($image_title, 25, '...') }} <i class="fa fa-external-link-square"></i></a></td>
                      <td>{{ $type }}</td>
                      <td>{{ date('d M, Y', strtotime($downloads->dateDownload)) }}</td>
                      <td>
                        @if ($image_photo == null)
                          <em>{{$image_title}}</em>
                        @else
                        <form method="POST" action="{{$downloadUrl}}" accept-charset="UTF-8" class="displayInline">
                          @csrf
                          <input name="downloadAgain" type="hidden" value="true">
                          <input name="type" type="hidden" value="{{$downloads->size ?: 'large'}}">
                          <button type="submit" class="btn btn-success btn-sm">
                            <i class="bi bi-download"></i>
                          </button>
                        </form>
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
