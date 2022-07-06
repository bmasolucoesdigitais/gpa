@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')


@can('master')
<h1>{{ __('general.Trainings') }}</h1>
@else
    <h1>{{ __('general.Training') }} - {{$training->name}} - {{ __('general.Employees') }}</h1>
@endcan


@stop

@section('content')



@canany(['master', 'G3 Company Edit'])
<a href="{{route('trainingschedule.add')}}" class="btn btn-app pull-right">
	<i class="fa fa-plus-square"></i> {{__('general.Add')}}
</a>
@endcanany


<div class="col-xs-12">
	<div class="box">


		<div class="box-body table-responsive">
			<table id="datatable" class="table table-hover table-striped">
				<thead><tr>


					<th>{{__('general.Name')}}</th>
					<th>CPF</th>
					<th>{{__('general.Remove')}}
				</tr>
			</thead>
			<tbody>
            @foreach ($employees as $employee)

				<tr>


					<td>{{ $employee->name}}</td>
					<td>{{ $employee->cpf}}</td>
					<td>

                        <a
                        id="btnDeleteTraining"
                        modalTitle="{{ __('general.Delete confirmation') }}"
                        modalBody="{{  __("general.Are you sure that you want to delete this ") }} {{ __("general.employee")  }} {{ __("general.from training")  }}?"
                        href=""
                        data-toggle="modal"
                        data-target="#confirmationModal"
                        idTr="{{ $training->id }}"
                        eId="{{ $employee->id }}"
                        class="btnDeleteTraining" >
                            <i class="fa fa-trash text-red"  style="font-size: 32px">
                            </i>
                        </a>
                </td>

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

        $('.btnDeleteTraining').click(function(){
			$('.modal-body').html($(this).attr("modalBody"));
			$('.modal-title').html($(this).attr("modalTitle"));
			id = $(this).attr("idTr");
			eid = $(this).attr("eId");
			tk = '{{ csrf_token() }}';
			action = "{{ route('provider.trainingreserve.detach') }}";
        });

        $('#deleteConfirm').click(function(){
            //$('.modal-body').append("Confirmar a exclusão deste arquivo"+ $(this).attr("file"));
            //alert(fileId);
            $('#confirmationModal').modal('hide');
            var data= {id:id, employee_id:eid, _token:tk};

            jQuery.ajax({
                type: "POST",
                url: action,
                data: data,
                success: function(data) {

                    if (data == 1) {
                        // console.log(data);
                        location.reload();

                    }else{
                        alert('Arquivo não encontrado!')
                    }
                }
            });
        });

	});

</script>


@endsection
@endsection
