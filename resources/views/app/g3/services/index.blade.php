@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')
<h1>{{__('general.Services')}}</h1>
<!-- metodo de entrada para translations {{ __('services.test') }} -->
@stop

@section('content')


@canany('master','G3 Admin','G3 Edit' )
<a href="{{route('services.add')}}" class="btn btn-app pull-right">
	<i class="fa fa-plus-square"></i> {{__('general.Add')}}
</a>
@endcanany

<div class="col-xs-12">
	<div class="box">

		<div  class="box-body table-responsive ">
			<table id="datatable" class="table table-hover table-striped">
				<thead>
				<tr>


					<th>{{__('general.Name')}}</th>
					<th>{{__('general.Description')}}</th>
					<th>{{__('general.Initials')}}</th>
					<th>{{__('general.Company')}}</th>
					<th>{{__('general.Documents')}}</th>
					@canany('master','G3 Admin','G3 Edit' )
					<th>{{__('general.Edit')}}</th>
<th>{{__('general.Delete')}}</th>
@endcanany
					<!-- <th>{{__('general.Delete')}}</th> -->
				</tr>
				</thead>
				<tbody>
				@foreach ($services as $service)

				<tr>


					<td>{{ $service->name}}</td>
					<td>{{ $service->description}}</td>
					<td>{{ $service->initials}}</td>
					<td>{{ $service->company->name}}</td>



					<td> <a href="{{route('services.documents', $service->id)}}">

						<i class="fa fa-folder text-yellow" style="font-size: 32px"></i>


					</a></td>
					@canany('master','G3 Admin','G3 Edit' )
<td>
						@if(Auth::User()->company_id == $service->company_id || Auth::User()->can('master') )
						<a class="	" href="{{route('services.edit', $service->id)}}">
							<i class="fa fa-pencil text-green" style="font-size: 32px"></i>
						</a>
					</td>
					<td>

						<a class="	" href="{{route('services.delete', $service->id)}}">
							<i class="fa fa-trash text-red" style="font-size: 32px"></i>
						</a>
						@else
						</td>
					<td>
						@endif
					</td>
@endif

					<!--</td>
					 <td>

						<a href="{{route('services.destroy', $service->id)}}">
						<i class="fa fa-trash-o text-red " style="font-size: 32px"></i>
						</a>
					</td>
				</tr> -->


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
