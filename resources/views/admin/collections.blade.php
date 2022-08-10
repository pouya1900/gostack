@extends('admin.layout')

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('misc.collections') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ __('misc.collections') }} ({{$data->total()}})</span>
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
                     <th class="active">{{ __('admin.user') }}</th>
                     <th class="active">{{ __('admin.title') }}</th>
										 <th class="active">{{ __('misc.images') }}</th>
                     <th class="active">{{ __('admin.type') }}</th>
                     <th class="active">{{ __('admin.date') }}</th>
                     <th class="active">{{ __('admin.actions') }}</th>
                   </tr>

                 @foreach ($data as $collection)
                   <tr>
                     <td>{{ $collection->id }}</td>
                     <td><a href="{{ url($collection->creator->username) }}" target="_blank">{{ $collection->creator->username }} <i class="bi-box-arrow-up-right"></i></a></td>
										 <td><a href="{{ url($collection->creator->username, 'collection').'/'.$collection->id }}" target="_blank">{{ $collection->title }} <i class="bi-box-arrow-up-right"></i></a></td>
										 <td>{{ $collection->collectionImages->count() }}</td>
                     <td>{{ __('misc.'.$collection->type.'') }}</td>

                     <td>{{ Helper::formatDate($collection->created_at) }}</td>
                     <td>
                      {!! Form::open([
                       'method' => 'POST',
                       'url' => 'panel/admin/collections',
                       'class' => 'displayInline'
                     ]) !!}
                  {!! Form::hidden('id', $collection->id); !!}
               {!! Form::button('<i class="bi-trash-fill"></i>', ['class' => 'btn btn-link text-danger e-none fs-5 p-0 actionDelete']) !!}

                 {!! Form::close() !!}
               </td>

                   </tr><!-- /.TR -->
                   @endforeach

									@else
										<h5 class="text-center p-5 text-muted fw-light m-0">{{ __('misc.no_results_found') }}</h5>
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
