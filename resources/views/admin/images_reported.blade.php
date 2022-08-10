@extends('admin.layout')

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ __('admin.images_reported') }} ({{$data->count()}})</span>
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

               @if ($data->count() !=  0)
                  <tr>
                     <th class="active">ID</th>
                     <th class="active">{{ trans('admin.report_by') }}</th>
                     <th class="active">{{ trans('admin.reported') }}</th>
                     <th class="active">{{ trans('admin.reason') }}</th>
                     <th class="active">{{ trans('admin.date') }}</th>
                     <th class="active">{{ trans('admin.actions') }}</th>
                   </tr>

                 @foreach ($data as $report)
                   <tr>
                     <td>{{ $report->id }}</td>
                     <td><a href="{{ url($report->user()->username) }}" target="_blank">{{ $report->user()->username }} <i class="bi-box-arrow-up-right"></i></a></td>
                     <td><a href="{{ url('photo', $report->image()->id) }}" target="_blank">{{ str_limit($report->image()->title, 10, '...') }} <i class="bi-box-arrow-up-right"></i></a></td>

                     @php if( $report->reason == 'copyright' ) {
               $reason = trans('admin.copyright');
                         } elseif( $report->reason == 'privacy_issue' ) {
               $reason = trans('admin.privacy_issue');
                         } else if( $report->reason == 'violent_sexual_content' ) {
               $reason = trans('admin.violent_sexual_content');
                         }

                       @endphp

                     <td>{{ $reason }}</td>

                     <td>{{ Helper::formatDate($report->created_at) }}</td>
                     <td>
                      {!! Form::open([
                       'method' => 'POST',
                       'url' => 'panel/admin/images-reported',
                       'class' => 'displayInline'
                     ]) !!}
                  {!! Form::hidden('id', $report->id); !!}
               {!! Form::button('<i class="bi-trash-fill"></i>', ['class' => 'btn btn-link text-danger e-none fs-5 p-0 actionDelete']) !!}

                 {!! Form::close() !!}
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

 		</div><!-- col-lg-12 -->
	</div><!-- end row -->
</div><!-- end content -->
@endsection
