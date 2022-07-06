@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')
@if(Request::is('g3/companies*'))
<h1>{{ $company->name }} - {{__('general.Employees')}}</h1>
@elseif(Auth::User()->can('master'))
<h1>{{__('general.Peoples')}}</h1>
@else
<h1>{{__('general.Outsourceds')}}</h1>
@endif


<!-- metodo de entrada para translations {{ __('employees.test') }} -->
@stop

@section('content')


{{-- <i class="fa  fa-check text-green " style="font-size: 32px"></i> Documentos validos
<i class="fa  fa-check text-yellow " style="font-size: 32px"></i> Autorização manual --}}
@canany(['master', 'G3 Employees Edit', 'fornecedor'])
@if(Request::is('g3/branches*'))
	<a href="{{route('branches.outsourceds.attach', $branch->id)}}" class="btn btn-app pull-right datatable">
		<i class="fa fa-plus-square"></i> {{__('general.Add')}}
	</a>

	<a href="{{route('branches')}}" class="btn btn-app pull-right">
		<i class="fa fa-arrow-left"></i> {{__('general.Branches')}}
	</a>

@else
<a href="{{route('employees.attach')}}" class="btn btn-app pull-right">
	<i class="fa fa-plus-square"></i> {{__('general.Add')}}
</a>
@endif
@endcanany





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
						@canany(['master', 'Admin', 'G3 Admin', 'fornecedor'])
						<th>Pasta</th>
						@endcanany
						{{-- <th>{{__('general.Documents')}}</th>
						<th>{{__('general.Services')}}</th> --}}
						@canany(['master', 'G3 Employees Edit', 'G3 Edit'] )
						<th>{{__('general.Edit')}}</th>
						<th>{{__('general.Delete')}}</th>
						@endcanany
					</tr>
				</thead>
				<tbody>
					@foreach ($employees as $employee)

					<tr>

						@if(Request::is('g3/branches*'))
							<td><a href="{{route('branches.outsourceds.outsourced', [$employee->id, $branch->id])}}">{{ $employee->name}}</a></td>
						@else
							<td><a href="{{route('employees.employee', $employee->id)}}">{{ $employee->name}}</a></td>
						@endif
						<td>{{ $employee->cpf}}</td>
						<td>{{ $employee->rg}}</td>
						<td>{{ date('d/m/Y', strtotime($employee->borndate))}}</td>
						<td>
						@can('manual')<a href="{{route('employees.allow', $employee->id)}}">@endcan
						@if($employee->allowed == 1)
						<i class="fa  fa-check text-green " style="font-size: 32px"></i>
						@else
							@if($employee->manual == 1 && strtotime($employee->dt_manual) > strtotime(Now()))
								<i data-toggle="tooltip" data-original-title="Por: {{$employee->allower->name}} - Até - {{date('d/m/Y', strtotime($employee->dt_manual))}} " class="fa  fa-check text-yellow " style="font-size: 32px"></i>
							@else
						<i class="fa  fa-times text-red " style="font-size: 32px"></i>
						@endif
						@endif
						@can('manual')</a>@endcan
						</td>
                        @canany(['master', 'G3 Admin', 'G3 Employee Edit', 'fornecedor'])

						<td>
							@if (Request::is('g3/branches*'))
								<a href="{{route('branches.outsourceds.documents', [$employee->id, $branch->id])}}">
							@else
								<a href="{{route('employees.documents_services', $employee->id)}}">
							@endif

							<i class="fa  fa-briefcase text-blues " style="font-size: 32px"></i>


						</a></td>
						@endcanany
						{{-- <td>
							@if (Request::is('g3/branches*'))
								<a href="{{route('branches.outsourceds.documents', [$employee->id, $branch->id])}}">
							@else
								<a href="{{route('employees.documents', $employee->id)}}">
							@endif

							<i class="fa  fa-folder text-yellow " style="font-size: 32px"></i>


						</a></td>
						<td>
						
								<a href="{{route('employees.services', $employee->id)}}">

							<i class="fa  fa-list " style="font-size: 32px"></i>


						</a></td> --}}
						@canany(['master', 'G3 Employees Edit', 'G3 Edit'] )
						<td>
							<a class="	" href="{{route('employees.edit', $employee->id )}}">

								<i class="fa fa-pencil text-green" style="font-size: 32px"></i>

							</a>
						</td>
						<td>
							@can('master')
							<a class="	" href="{{route('employees.delete', $employee->id)}}">

								<i class="fa fa-trash text-red" style="font-size: 32px"></i>

							</a>
							@else
								<a class="	" href="{{route('employees.detach', [Auth::User()->company_id, $employee->id])}}">

								<i class="fa fa-trash text-red" style="font-size: 32px"></i>

							</a>
							@endcanany
						</td>
@endif
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
