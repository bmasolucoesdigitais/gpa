@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')

@if (Request::is('g3/branches*'))
	<h1>{{ __('general.Branches') }} - {{ $branch->name }} - {{ __('general.Providers') }}</h1>
@else
	@can('master')
	<h1>{{ __('general.Companies') }}</h1>
	@else
	 <h1>{{ __('general.Providers') }}</h1>
	@endcan
@endif

@stop

@section('content')


@can('master')
<a href="{{route('companies.add')}}" class="btn btn-app pull-right">
	<i class="fa fa-plus-square"></i>{{__('general.Add')}}
</a>
@endcan
@can('G3 Company Edit')
@cannot('master')
@if(Request::is('g3/branches*'))
<a href="{{route('branches.clients.attach', [$branch->id])}}" class="btn btn-app pull-right">
@else
<a href="{{route('companies.attach')}}" class="btn btn-app pull-right">
@endif
	<i class="fa fa-plus-square"></i>{{__('general.Add')}}
</a>
@endcannot
@endcan

@if(Request::is('g3/branches*'))
<a href="{{route('branches')}}" class="btn btn-app pull-right">
 	<i class="fa fa-arrow-left"></i>{{__('general.Branches')}}
</a>
@endif

<div class="col-xs-12">
	<div class="box">


		<div class="box-body table-responsive">
			<table id="datatable" class="table table-hover table-striped">
				<thead><tr>


					<th>{{__('general.Name')}}</th>
					<th>{{__('general.cnpj')}}</th>
                    <th>{{__('general.Employees')}}</th>
                    @if(Request::is('*g3/clients*'))
                        @can('master')<th>{{__('general.Outsourceds')}}</th>@endcan
                        @can('master')<th>{{__('general.Providers')}}</th>@endcan
                    @endif
					<th>{{__('general.Documents')}}</th>
					@canany(['master', 'G3 Admin'])
                        <th>{{__('general.Schedules')}}</th>
                    @endcanany
					@can('master')<th>{{__('general.Edit')}}</th>@endcan
					@canany('master', 'G3 Edit')<th>{{__('general.Delete')}}</th>@endcanany
				</tr>
			</thead>
			<tbody>
				@foreach ($companies as $company)

				<tr>


					<td>{{ $company->name}}</td>
					<td>{{ $company->cnpj}}</td>




<td>

	@if(Request::is('g3/branches*'))
		<a href="{{route('branches.clients.employees', [$company->id, $branch->id])}}">
		<i class="fa fa-users" style="font-size: 32px"></i>
		</a>
	@else

		<a href="{{route('companies.employees', $company->id)}}">
		<i class="fa fa-users" style="font-size: 32px"></i>
		</a>

	@endif
    </td>
    @if(Request::is('*g3/clients*'))
        @can('master')
            <td>

                <a href="{{route('companies.outsourceds', $company->id)}}">
                <i class="fa fa-exchange " style="font-size: 32px"></i>
                </a>


            </td>
        @endcan
        @can('master')
            <td>
                <a href="{{route('companies.clients', $company->id)}}">
                <i class="fa fa-industry " style="font-size: 32px"></i>
                </a>
            </td>
        @endcan
    @endif
<td>
	@if (Request::is('g3/branches*'))
		<a class="	" href="{{route('branches.clients.documents', [$company->id, $branch->id] )}}">
	@else
		<a class="	" href="{{route('companies.documents', $company->id )}}">
	@endif

		<i class="fa fa-folder text-yellow" style="font-size: 32px"></i>

	</a>
</td>
<td>
    @canany(['master', 'G3 Admin'])

    <a href="{{route('companies.servicesscheduled', $company->id)}}">
        <i class="fa fa-calendar text-blue" style="font-size: 32px"></i>
    </a>
    @endcanany

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
	@canany('master', 'G3 Company Edit', 'G3 Edit')
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
