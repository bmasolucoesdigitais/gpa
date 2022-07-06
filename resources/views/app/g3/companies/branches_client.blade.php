@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')
<h1>{{ __('general.Companies') }}</h1>

@stop

@section('content')


@can('master')
<a href="{{route('companies.add')}}" class="btn btn-app pull-right">
	<i class="fa fa-plus-square"></i> {{__('general.Add')}}
</a>
@else
<a href="{{route('companies.attach')}}" class="btn btn-app pull-right">
	<i class="fa fa-plus-square"></i> {{__('general.Add')}}
</a>
@endcan

<div class="col-xs-12">
	<div class="box">
		

		<div class="box-body table-responsive">
			<table id="datatable" class="table table-hover table-striped">
				<thead><tr>

					
					<th>{{__('general.Name')}}</th>
					<th>{{__('general.cnpj')}}</th>
					<th>{{__('general.Employees')}}</th>
					<th>{{__('general.Documents')}}
					<th>{{__('general.Edit')}}</th>
					<th>{{__('general.Delete')}}</th>
				</tr>
			</thead>
			<tbody>
				@if($companies)
				@foreach ($companies as $company)

				<tr>

					
					<td>{{ $company->name}}</td>
					<td>{{ $company->cnpj}}</td>
					



<td> <a href="{{route('employees', [$owner->id, $company->id])}}">

	<i class="fa fa-users" style="font-size: 32px"></i>


</a></td>

<td>
	<a class="	" href="{{route('companies.branches.client.documents', [$owner->id, $company->id] )}}">

		<i class="fa fa-folder text-yellow" style="font-size: 32px"></i>

	</a>
</td>

<td>
	<a class="	" href="{{route('companies.branches.client.edit', [$owner->id, $company->id] )}}">

		<i class="fa fa-pencil text-green" style="font-size: 32px"></i>

	</a>
</td>

<td> 
	
		<a href="{{route('companies.branches.client.detach', [$owner->id, $company->id])}}">
	<i class="fa fa-trash-o text-red " style="font-size: 32px"></i>
	</a>
	

</td>
</tr>


	@endforeach
	@endif

</tbody></table>
</div>

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
