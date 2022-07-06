@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')
<h1>{{__('general.Delivereds')}}</h1>
<!-- metodo de entrada para translations {{ __('documents.test') }} -->
@stop

@section('content')




<a href="{{route('delivereds.add')}}" class="btn btn-app pull-right">
	<i class="fa fa-plus-square"></i> Add
</a>

<div class="col-xs-12">
	<div class="box">
		
		<div class="box-body table-responsive ">
			<table id="datatable" class="table table-hover">
				<thead>
					<tr>
						
						<th>{{__('general.Description')}}</th>
						<th>{{__('general.Expires')}}</th>
						<th>{{__('general.Document')}}</th>
						<th>{{__('general.Employee')}}</th>
						<th>{{__('general.Company')}}</th>
						<th>{{__('general.Edit')}}</th>
						<th>{{__('general.Delete')}}</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($delivereds as $delivered)

					<tr>

						
						<td>{{ $delivered->description}}</td>
						<td>{{ date('d/m/Y', strtotime($delivered->expiration))}}</td>
						<td>{{ $delivered->document->name}}</td>
						<td>@if($delivered->employee){{ $delivered->employee->name}}@endif</td>
						<td>@if($delivered->company){{ $delivered->company->name}}@endif</td>



						<td>
							<a class="	" href="{{route('delivereds.edit', $delivered->id)}}">

								<i class="fa fa-pencil-square" style="font-size: 32px"></i>

							</a>
						</td>
						<td> <a href="{{route('delivereds.delete', $delivered->id)}}">

							<i class="fa fa-trash-o text-red " style="font-size: 32px"></i>


						</a></td>
					</tr>

					@endforeach

				</tbody></table>
			</div>
			<!-- /.box-body -->
		</div>
		<!-- /.box -->
	</div>
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
	@endsection
