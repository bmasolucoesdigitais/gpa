@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')
@if (Request::is('g3/branches*'))
	<h1>{{__('general.Branches')}} - {{ $branch->name }} - {{ __('general.Providers') }} - {{$company->name}} - {{__('general.Employees')}}</h1>
@else
	<h1>{{__('general.Companies')}} - {{$company->name}} - {{__('general.Employees')}}</h1>
@endif

@stop

@section('content')


@canany('master', 'G3 Edit')

@if(Request::is('g3/branches*'))
	<a href="{{route('branches.clients.employees.add', [$company->id, $branch->id])}}" class="btn btn-app pull-right datatable">
@else
	<a href="{{route('companies.employees.add', $company->id)}}" class="btn btn-app pull-right datatable">
@endif
	<i class="fa fa-paperclip"></i> {{__('general.Link')}}
</a>

@if(Request::is('g3/branches*'))
	<a href="{{route('branches.clients.employees.create', [$company->id, $branch->id])}}" class="btn btn-app pull-right datatable">
@else
	<a href="{{route('companies.employees.create', $company->id)}}" class="btn btn-app pull-right datatable">
@endif
	<i class="fa fa-plus-square"></i> {{__('general.Add')}}
</a>


@endcanany

@if(Request::is('g3/branches*'))
	<a href="{{route('branches.clients', $branch->id)}}" class="btn btn-app pull-right datatable">
		<i class="fa fa-arrow-left"></i> {{__('general.Providers')}}
	</a>

@else
	<a href="{{route('companies')}}" class="btn btn-app pull-right">
		<i class="fa fa-arrow-left"></i> {{__('general.Companies')}}
	</a>
@endif

<div class="col-xs-12">
	<div class="box">

		<div class="box-body table-responsive">
			<table id="datatable" class="table table-hover">
				<thead><tr>


					<th>{{__('general.Name')}}</th>
					<th>{{__('general.cpf')}}</th>

					@canany('master', 'G3 Edit' )
					<th>{{__('general.Delete')}}</th>
					@endcanany
				</tr>
			</thead>
			<tbody>
				@foreach ($employees as $employee)

				<tr>


					<td>{{ $employee->name}}</td>
					<td>{{ $employee->cpf}}</td>





					@canany(['master', 'G3 Edit'])
					<td>
						@if (Request::is('g3/branches*'))
							<a href="{{route('branches.clients.employees.delete', [$company->id, $employee->id, $branch->id ])}}">
						@else
							<a href="{{route('companies.employees.delete', [$company->id, $employee->id ])}}">
						@endif

						<i class="fa fa-trash-o text-red " style="font-size: 32px"></i>


					</a></td>
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
	});
</script>


@endsection
@endsection
