@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')

@if (Request::is('g3/branches*'))
	<h1>{{__('general.Branches')}} - {{ $branch->name }} - {{$data['employee']->name}} - {{__('general.Services')}}</h1>
@else
	<h1>{{__('general.Employees')}} - {{$data['employee']->name}} - {{__('general.services')}}</h1>
@endif
<!-- metodo de entrada para translations {{ __('employees.test') }} -->
@stop

@section('content')
@canany(['master', 'G3 Employees Edit', 'G3 Edit', 'fornecedor' ])
@if (Request::is('g3/branches*'))
<a href="{{route('branches.outsourceds.services.attach', [$data['employee']->id, $branch->id])}}" class="btn btn-app pull-right">
@else
<a href="{{route('employees.services.add', $data['employee']->id)}}" class="btn btn-app pull-right">
@endif
	<i class="fa fa-plus-square"></i> {{__('general.Add')}}
</a>

@endcanany
@if (Request::is('g3/branches*'))
	<a href="{{route('branches.outsourceds', $branch->id)}}" class="btn btn-app pull-right">
@else
	<a href="{{route('employees.index')}}" class="btn btn-app pull-right">
@endif
	<i class="fa fa-arrow-left"></i> {{__('general.Employees')}}
</a>


<div class="col-xs-12">
	<div class="box">

		<div class="box-body table-responsive ">
			<table id="datatable" class="table table-hover">
				<thead>

					<tr>

						<th>{{__('general.Initials')}}</th>
						<th>{{__('general.Name')}}</th>
						<th>{{__('general.Documents')}}</th>
						@canany(['master', 'G3 Employees Edit', 'G3 Edit', 'fornecedor'] )
						<th>{{__('general.Delete')}}</th>
						@endcanany

					</tr>
				</thead>

				<tbody>


					@foreach($data['services'] as $service)
					<tr>

						<td>{{ $service->initials}}</td>
						<td>{{ $service->name}}</td>
						<td>
							@if (Request::is('g3/branches*'))
								{{-- expr --}}
								<a class="	" href="{{route('branches.outsourceds.services.delivereds', [$data['employee']->id, $service->id, $branch->id] )}}">
							@else
								<a class="	" href="{{route('employees.delivereds', [$data['employee']->id, $service->id] )}}">
							@endif
								<i class="fa fa-folder text-yellow" style="font-size: 32px"></i>
							</a>
						</td>
						@canany(['master', 'G3 Employees Edit', 'G3 Edit', 'fornecedor'] )
							<td>
							<a id="btnDeleteService"
							modalTitle="{{ __('general.Delete confirmation') }}"
							modalBody=" {{ __("general.Are you sure that you want to delete this ").__("general.service") }} ?"
							serviceId="{{ $service->id }}"
							employeeId="{{$data['employee']->id }}"
							class="btnDeleteService " href=""
							data-toggle="modal"
							data-target="#confirmationModal
							"><i class="fa fa-trash text-red" style="font-size: 32px"></i></a>
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


			$('.btnDeleteService').click(function(){
				$('.modal-body').html($(this).attr("modalBody"));
				$('.modal-title').html($(this).attr("modalTitle"));
				id = $(this).attr("serviceId");
				employeeId = $(this).attr("employeeId");
				action = "{{ route('employees.services.detach') }}?_token={{ csrf_token() }}";
					//alert($(this).attr("fileId"));

				});

			$('#deleteConfirm').click(function(){
					//$('.modal-body').append("Confirmar a exclusão deste arquivo"+ $(this).attr("file"));
				//alert(fileId);
				$('#confirmationModal').modal('hide');
				var data= {id:id, eid: employeeId};

				jQuery.ajax({
					type: "POST",
					url: action,
					data: data,
					success: function(data) {

						if (data == 1) {
					        // console.log(data);
					        location.reload();

					    }else{
					    	alert('Serviço não encontrado!')
					    }
					}
				});
			});
		});
	</script>


	@endsection
	@endsection
