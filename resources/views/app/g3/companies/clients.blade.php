@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')

	<h1>{{ __('general.Stores') }}</h1>


@stop

@section('content')




<div class="col-xs-12">
	<div class="box">


		<div class="box-body table-responsive">
			<table id="datatable" class="table table-hover table-striped">
				<thead><tr>


					<th>{{__('general.Name')}}</th>
					<th>{{__('general.Branch')}}</th>
					<th>{{__('general.Flag')}}</th>
					<th>{{__('general.cnpj')}}</th>
                    <th>{{__('general.Services')}}</th>

                    <th>{{__('general.Outsourceds')}}</th>
                    <th>{{__('general.Providers')}}</th>

					<th>{{__('general.Documents')}}
					@can('master')
					<th>{{__('general.Edit')}}</th>
					<th>{{__('general.Delete')}}</th>
					@endcan
				</tr>
			</thead>
			<tbody>
				@foreach ($companies as $company)

				<tr>


					<td>{{ $company->name}}</td>
					<td>{{ $company->filial}}</td>
					<td>{{ $company->flag}}</td>
					<td>{{ $company->cnpj}}</td>




			<td>
				<a href="{{route('clients.servicesscheduled.listcompany', $company->id)}}">
				<i class="fa fa-calendar text-default " style="font-size: 32px"></i>
				</a>
			</td>

            <td>

                <a href="{{route('clients.outsourceds', $company->id)}}">
                <i class="fa fa-exchange " style="font-size: 32px"></i>
                </a>


            </td>

            <td>
                <a href="{{route('clients.providers', $company->id)}}">
                <i class="fa fa-industry " style="font-size: 32px"></i>
                </a>
            </td>

<td>
	
		<a class="	" href="{{route('companies.documents', $company->id )}}">

		<i class="fa fa-folder text-yellow" style="font-size: 32px"></i>

	</a>
</td>

@can('master')
<td>
	<a class="	" href="{{route('companies.edit', $company->id )}}">

		<i class="fa fa-pencil text-green" style="font-size: 32px"></i>

	</a>
</td>
@endcan


	@can('master')
<td>
	<a href="{{route('companies.delete', $company->id)}}">
	<i class="fa fa-trash-o text-red " style="font-size: 32px"></i>
	</a>
	@else
	@canany('master', 'G3 Edit')
	<td>
		@if (Request::is('g3/branches*'))
			<a href="{{route('branches.clients.detach', ['cp'=>0,'id'=>$company->id, $branch->id])}}">
		@else
			<a href="{{route('companies.detach', ['cp'=>0,'id'=>$company->id])}}">
		@endif
	<i class="fa fa-trash-o text-red " style="font-size: 32px"></i>
	</a>
</td>
@endcanany
	@endcan

</tr>


	@endforeach

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
