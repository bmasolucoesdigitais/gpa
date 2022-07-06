@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')
<h1>{{__('general.Employees')}}</h1>
<!-- metodo de entrada para translations {{ __('employees.test') }} -->
@stop

@section('content')



<a href="{{route('employees.attach')}}" class="btn btn-app pull-right">
	<i class="fa fa-plus-square"></i> {{__('general.Add')}}
</a>

<div class="col-xs-12">
	<div class="box">
		
		<div class="box-body table-responsive">
			<table id="datatable" class="table table-hover">
				<thead>
					<tr>
						
						<th>{{__('general.Name')}}</th>
						<th>{{__('general.cpf')}}</th>
						<th>{{__('general.rg')}}</th>
						<th>{{__('general.Borndate')}}</th>
						<th>{{__('general.Allowed')}}</th>
						<th>{{__('general.Documents')}}</th>
						<th>{{__('general.Services')}}</th>
						<th>{{__('general.Edit')}}</th>
						<th>{{__('general.Delete')}}</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($employees as $employee)
					
					<tr>

						
						<td><a href="{{route('employees.employee', $employee->id)}}">{{ $employee->name}}</a></td>
						<td>{{ $employee->cpf}}</td>
						<td>{{ $employee->rg}}</td>
						<td>{{ date('d/m/Y', strtotime($employee->borndate))}}</td>
						<td>{{ $employee->allowed}}</td>

						<td> <a href="{{route('employees.documents', $employee->id)}}">

							<i class="fa  fa-folder text-yellow " style="font-size: 32px"></i>


						</a></td>
						<td> <a href="{{route('employees.services', $employee->id)}}">

							<i class="fa  fa-list " style="font-size: 32px"></i>


						</a></td>
								</a></td>
						<td>
							<a class="	" href="{{route('employees.edit', $employee->id )}}">

								<i class="fa fa-pencil text-green" style="font-size: 32px"></i>

							</a>
						</td>
						<td>
							
								<a class="	" href="{{route('employees.detach', $employee->id)}}">

								<i class="fa fa-trash text-red" style="font-size: 32px"></i>

							</a>
							
						</td>
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
