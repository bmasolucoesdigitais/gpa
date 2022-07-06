@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')

@if(Request::is('g3/branches*'))
<h1>{{__('general.Branches')}} - {{$company->name}} - {{__('general.Outsourceds')}}</h1>
@else
<h1>{{__('general.Companies')}} - {{$company->name}} - {{__('general.Outsourceds')}}</h1>
@endif

@stop

@section('content')


<i class="fa  fa-check text-green " style="font-size: 32px"></i> Documentos validos
<i class="fa  fa-check text-yellow " style="font-size: 32px"></i> Autorização manual


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
						@canany(['master', 'fornecedor', 'G3 Admin', 'cd', 'tecnico'])
						<th>{{__('general.Documents')}}</th>
						<th>{{__('general.Services')}}</th>
						@endcanany
						@canany(['master', 'fornecedor'])
						<th>{{__('general.Edit')}}</th>
						<th>{{__('general.Delete')}}</th>
						@endcanany
					</tr>
				</thead>
				<tbody>
					@foreach ($employees as $employee)

					<tr>


						<td><a href="{{route('employees.employee', $employee->id)}}">{{ $employee->name}}</a></td>
						<td>{{ $employee->cpf}}</td>
						<td>{{ $employee->rg}}</td>
						<td>{{ date('d/m/Y', strtotime($employee->borndate))}}</td>
						
						@canany(['master', 'fornecedor', 'G3 Admin', 'cd', 'tecnico'])
						<td>
							@if(Request::is('g3/clients*'))
								<a href="{{route('clients.employees.documents', [$company->id, $employee->id ])}}">
							@else
								<a href="{{route('employees.documents', $employee->id)}}">
							@endif
							<i class="fa  fa-folder text-yellow " style="font-size: 32px"></i>


						</a></td>

						<td> <a href="{{route('employees.services', $employee->id)}}">

							<i class="fa  fa-list " style="font-size: 32px"></i>


						</a></td>
					</a></td>
					@endcanany
					@canany(['master', 'fornecedor'])

					<td>
						<a class="	" href="{{route('employees.edit', $employee->id )}}">

							<i class="fa fa-pencil text-green" style="font-size: 32px"></i>

						</a>
					</td>
					<td>

						<a class="	" href="{{route('employees.detach', [$company->id, $employee->id])}}">

							<i class="fa fa-trash text-red" style="font-size: 32px"></i>

						</a>

					</td>
					@endcanany
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
		$('[data-toggle="tooltip"]').tooltip({
			placement : 'top'
		});
	});
</script>

@endsection
@endsection
