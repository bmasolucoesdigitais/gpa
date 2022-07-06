@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')
<h1>{{__('general.Services')}} - {{$service->name}} - {{__('general.documents')}}</h1>
<!-- metodo de entrada para translations {{ __('employees.test') }} -->
@stop

@section('content')


@if(Auth::User()->hasAnyPermission('master','G3 Admin','G3 Edit' ))
<a href="{{route('services.documents.add', $service->id)}}" class="btn btn-app pull-right">
	<i class="fa fa-pencil"></i> {{__('general.Edit')}}
</a>
@endif
<a href="{{route('services.index')}}" class="btn btn-app pull-right">
	<i class="fa fa-arrow-left"></i> {{__('general.Services')}}
</a>


<div class="col-xs-12">
	<div class="box">

		<div class="box-body table-responsive">
			<table id="datatable" class="table table-hover table-striped">

				<thead><tr>


					<th>{{__('general.Name')}}</th>
					<th>{{__('general.Description')}}</th>

				</tr>
				</thead>
				<tbody>
				@foreach($service->documents as $document)
				<tr>

					<td>{{ $document->name}}</td>
					<td>{{ $document->description}}</td>




				</tr>


				@endforeach



			</tbody></table>
		</div>
		<!-- /.box-body -->
	</div>
	<!-- /.box -->
</div>
@stop
@section('js')
<script type="text/javascript">

$( document ).ready(function() {
$('#datatable').DataTable( {
 			"initComplete": function(settings, json) {
    			$('div.dataTables_filter input').focus();
  			}
		});
});
</script>


@endsection
