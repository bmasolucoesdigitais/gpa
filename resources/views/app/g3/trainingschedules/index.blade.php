@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')


@can('master')
<h1>{{ __('general.Trainings') }}</h1>
@else
    <h1>{{ __('general.Trainings') }}</h1>
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
					@canany(['master', 'G3 Company Edit', 'G3 Documents View'])<th>{{__('general.Company')}}</th>@endcan
					<th>{{__('general.Init')}}</th>
					<th>{{__('general.End')}}</th>
					<th>{{__('general.Vacancies')}}
					<th>{{__('general.Subscriptions')}}
					<th>{{__('general.Accomplished')}}
					<th>{{__('general.Reserve')}}
                    @canany(['master', 'G3 Company Edit'])<th>{{__('general.Edit')}}@endcan
                    @canany(['master', 'G3 Company Edit'])<th>{{__('general.Delete')}}@endcan
				</tr>
			</thead>
			<tbody>
            @foreach ($trainings as $training)

				<tr>


					<td>{{ $training->name}}</td>
					<td>{{ $training->company->name}}</td>
					<td>{{preg_replace('#(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2})#', '$3/$2/$1 $4:$5', $training->dt_ini)}}</td>
					<td>{{preg_replace('#(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2})#', '$3/$2/$1 $4:$5', $training->dt_end)}}</td>
					<td>{{ $training->vacancies}}</td>
					<td>
                        @if ($training->students->count() > 0)
                            <a class="" href="{{route('trainingschedule.employees', $training->id )}}">
                                {{ $training->students->count()}}
                            </a>
                        @else
                            {{ $training->students->count()}}
                        @endif

                    </td>
					<td>
                        @if ($training->fl_accomplished == 1)
                            <a href="" class="changeAccomplished" data-id="{{$training->id}}">
                                <i class="fa fa-check text-green"  style="font-size: 32px"></i>
                            </a>
                            @else
                            <a href="" class="changeAccomplished" data-id="{{$training->id}}">
                                <i class="fa fa-times text-red"  style="font-size: 32px"></i>
                            </a>
                        @endif
                        {{ $training->acco}}
                    </td>
                    <td>
                        @if ($training->vacancies - $training->students()->count() > 0)
                        <a class="" href="{{route('provider.trainingreserve.attach', $training->id )}}">

                            <i class="fa fa-plus-square text-green" style="font-size: 32px"></i>

                        </a>

                        @endif

                    </td>
                    @canany(['master', 'G3 Company Edit'])
                    <td>
                        <a class="" href="{{route('trainingschedule.edit', $training->id )}}">

                            <i class="fa fa-pencil text-green" style="font-size: 32px"></i>

                        </a>
                    </td>
                        <td>
                            <a
                            id="btnDeleteTraining"
							modalTitle="{{ __('general.Delete confirmation') }}"
							modalBody="{{  __("general.Are you sure that you want to delete this ") }} {{ __("general.training")  }}?"
							href=""
							data-toggle="modal"
							data-target="#confirmationModal"
							idTr="{{ $training->id }}"
							class="btnDeleteTraining" >

                            <i class="fa fa-trash-o text-red " style="font-size: 32px"></i>
                            </a>
                        </td>
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
  			},
            "drawCallback": function( settings ) {
                 //alert( 'DataTables has redrawn the table' );
                $('.changeAccomplished').click(function (){
                    event.preventDefault();
                    target = $(this);
                    //console.log("change accomplished");
                    var id = $(this).attr('data-id');
                    //console.log(id);
                    tk = '{{ csrf_token() }}';
                    var data = {id:id, _token:tk};
                        jQuery.ajax({
                            type: "POST",
                            url: "/g3/trainingschedule/changeaccomplished",
                            data: data,
                            success: function(data) {

                                if (data == 1) {
                                    // console.log(data);
                                    $(target).html('<i class="fa fa-check text-green" style="font-size: 32px"></i>');
                                }else if(data == 0){
                                    $(target).html('<i class="fa fa-times text-red" style="font-size: 32px"></i>');
                                }else{
                                    alert('Erro ao mudar status');
                                }
                            }
                        });
                });
            }
		});

        $('.btnDeleteTraining').click(function(){
            console.log('TESTE');
            console.log($(this));
			$('.modal-body').html($(this).attr("modalBody"));
			$('.modal-title').html($(this).attr("modalTitle"));
			id = $(this).attr("idTr");
			tk = '{{ csrf_token() }}';
			action = "{{ route('trainingschedule.delete') }}";
        });

        $('#deleteConfirm').click(function(){
            //$('.modal-body').append("Confirmar a exclusão deste arquivo"+ $(this).attr("file"));
            //alert(fileId);
            $('#confirmationModal').modal('hide');
            var data= {id:id, _token:tk};

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
