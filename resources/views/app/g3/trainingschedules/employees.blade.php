@extends('adminlte::page')

@section('title', 'Lista de Inscritos')

@section('content_header')
<style>
    {    
    .no-print, .no-print *
    {
        display: none !important;
    }
}
</style>

@can('master')
<h1>{{ __('general.Trainings') }} - {{$training->name}} - {{ __('general.Employees') }}</h1>
@else
    <h1>{{ __('general.Training') }} - {{$training->name}} - {{ __('general.Employees') }}</h1>
@endcan


@stop

@section('content')




<div class="col-xs-12">
  
	<div class="box">




		<div class="box-body table-responsive">
			<table id="datatable" class="table table-hover table-striped">
				<thead><tr>


					<th>{{__('general.Name')}}</th>
					<th>CPF</th>
					<th >{{__('general.Company')}}</th>
					<th>{{__('general.Test')}}</th>
					<th>{{__('general.Present')}}</th>
					<th>{{__('general.Status')}}</th>
					<th >{{__('general.Remove')}}</th>
					<th width="20%">Assinatura:</th>

				</tr>
			</thead>
			<tbody>
            @foreach ($employees as $employee)

				<tr>


					<td>{{ $employee->name}}</td>
					<td>{{ $employee->cpf}}</td>
					<td>@isset($employee->companies()->get()[0])
                        {{ $employee->companies()->get()[0]->name}}</td>
                    @endisset 
					<td>
                        @if($employee->pivot->token)
                        <a href="/prova/{{$employee->pivot->token}}" target="_blank" class="" data-student="">
                            <i class="fa fa-file text-green"   style="font-size: 32px"></i>
                        </a>
                        @else
                        
                            <i class="fa fa-times text-red"   style="font-size: 32px"></i>
                        
                        @endif
                    </td>
					<td>
                        @if($employee->pivot->fl_present == 1)
                            <a href="" class="changePresence" data-student="{{$employee->id}}">
                                <i class="fa fa-check text-green"   style="font-size: 32px"><span class="visible-print">P</span></i>
                            </a>
                        @else
                            <a href="" class="changePresence" data-student="{{$employee->id}}">
                                <i class="fa fa-times text-red"   style="font-size: 32px"><span class="visible-print">F</span></i>
                            </a>
                        @endif
                    </td>

                    <td>
                        @if($employee->pivot->status_test == 1)
                           <p class="text-yellow">Não fez</p>
                        @endif
                        @if($employee->pivot->status_test == 2)
                           <p class="text-green">Aprovado 1a vez</p>
                        @endif
                        @if($employee->pivot->status_test == 3)
                           <p class="text-yellow">Reprovado 1a vez</p>
                        @endif
                        @if($employee->pivot->status_test == 4)
                           <p class="text-green">Aprovado 2a vez</p>
                        @endif
                        @if($employee->pivot->status_test == 5)
                           <p class="text-red">Reprovado 2a vez</p>
                        @endif
                            
                    </td>
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
                <td>____________________</td>

                </tr>

	        @endforeach

</tbody></table>
</div>

</div>
@section('js')
<script type="text/javascript">


	$( document ).ready(function() {
		$('#datatable').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            {
           extend: 'print',
           exportOptions: {
                columns: [ 0, 1, 2, 4, 7 ] //Your Column value those you want
               },
                customize: function(win) {
                $(win.document.body).prepend(' <h1> {{$training->name}} </h1> <div class="col-xs-12">     </p>                 <strong>                   SHE:                 </strong>                DNPAM-OPS-SOP-014145 PLANO DE RESPOSTA A EMERGENCIAS, DNPAM-OPS-POL-108006 POLITICA DE SHE, 11 Requerimentos de SHE, DNPAM-OPS-SOP-109207 COMUNICACAO de SHE, Avaliação de Risco, Segurança Baseada em Comportamento, Atitudes Seguras Pre Atividade, Regras Gerais de SHE, Segurança de Processo, 12 Regras Salva Vidas, Gerenciamento de Consequencias, Informacoes sobre COVID-19, Funcionamento do Ambulatorio, Atestados e Licencas, Security (Regras gerais), DNPAM-OPS-SOP-109185 LAIA / DNPAM-OPS-SOP-109186 OBJETIVOS AMBIENTAIS, DNPAM-OPS-SOP-109303 GERENCIAMENTO DE RESIDUOS, DNPAM-OPS-SOP-109282 GERENCIAMENTO DE SUBSTANCIAS QUIMICAS.            <p>                            <p>                <strong>                    QUALIDADE:                 </strong>                Boas Praticas de Fabricacao, Segurança dos Alimentos , Controle de Pragas, Programa 5S, 8 Compromissos com os Clientes.                        </p>        </div>'); 
                //after the table
                    $(win.document.body).append(''); //before the table
            },
            }
            
        ],
                "paging": false, 
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
        $('.changePresence').click(function(){
            //$('.modal-body').append("Confirmar a exclusão deste arquivo"+ $(this).attr("file"));
            //alert(fileId);
            event.preventDefault();
            student = $(this).attr('data-student')
            id = {{ $training->id }}
            tk = '{{ csrf_token() }}';
            var data= {id:id, student:student, _token:tk};
            console.log(data);
            jQuery.ajax({
                type: "POST",
                url: '/g3/trainingschedule/changepresence',
                data: data,
                success: function(data) {

                    console.log(data);
                        location.reload();
                    if (data == 1) {

                    }else{
                        //alert('Arquivo não encontrado!')
                    }
                }
            });
        });

	});

</script>


@endsection
@endsection
