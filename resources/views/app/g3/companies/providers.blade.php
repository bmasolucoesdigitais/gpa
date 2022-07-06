@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')
<h1>{{$owner->name}} - {{ __('general.Providers') }}</h1>

@stop

@section('content')



<a href="{{route('clients.providers.attach', $owner->id)}}" class="btn btn-app pull-right">
	<i class="fa fa-plus-square"></i> {{__('general.Add')}}
</a>

 	<a href="{{route('clients')}}" class="btn btn-app pull-right">
 	<i class="fa fa-arrow-left"></i> {{__('general.Clients')}}
</a>


@if(Request::is('*g3/branches/clients*'))
 	<a href="{{route('branches')}}" class="btn btn-app pull-right">
 	<i class="fa fa-arrow-left"></i> {{__('general.Branches')}}
</a>
@endif


<div class="col-xs-12">
	<div class="box">


		<div class="box-body table-responsive">
			<table id="datatable" class="table table-hover table-striped">
				<thead><tr>

					<th>id</th>
					<th>{{__('general.Name')}}</th>
					<th>{{__('general.Allowed')}}</th>
					<th>{{__('general.Documents')}}</th>
					<th>{{__('general.Services')}}</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($companies as $company)

				<tr>

					<td>{{ $company->id}}</td>
					<td>{{ $company->name}}</td>
                    <td>
@php
    $documents = $company->documents;
    $aprovado = 1;
    foreach ($documents as $document) {
        $delivered = $document->delivereds()->where('company_id', $company->id)->where('fl_deleted', 0)->orderBy('id', 'desc')->first();
       /*  echo strtotime($delivered->expiration);
        echo '<br>';
        echo strtotime(date('Y-m-d'));
        echo '<br>'; */
        //var_dump($delivered);
        if( $delivered){

            if($delivered->status > 0 ){
                $aprovado = 0;
            }
            if(strtotime($delivered->expiration) < strtotime(date('Y-m-d')) ){
                $aprovado = 0;
            }
        }else{
            $aprovado = 0;
        }
        
    }
    if ($aprovado == 1) {
            echo '<i class="fa fa-check text-green" style="font-size: 32px"></i>';
            # code...
        }else{
            echo '<i class="fa fa-times text-red" style="font-size: 32px"></i>';

        }
@endphp

                    </td>
                    <td>
                        <a class="" href="{{route('clients.documents', $company->id )}}">
                            <i class="fa fa-folder text-yellow" style="font-size: 32px"></i>
                        </a>
                    </td>
                    <td>
                        <a class="" href="{{route('companies.servicesscheduled.listcompany', $company->id)}}">
                            <i class="fa fa-calendar text-default" style="font-size: 32px"></i>
                        </a>
                    </td>







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
        $('#datatable').on( 'draw.dt', function () {
           // alert( 'Table redraw' );
            saveActions();
        } );
        function saveActions(){
            $('.save').click( function(event){
                event.preventDefault();
                console.log($(this).attr('href'));
                console.log($('#client_'+$(this).attr('href')).val());

                var cp = $(this).attr("href");
                var company = $('#company_'+$(this).attr('href')).val()
                var provider = $('#provider_'+$(this).attr('href')).val()
                var data= {'company':company, 'provider':provider, 'cp':cp };
                var action = 'savemail?_token={{ csrf_token() }}';
                var clicado = $(this)
                var clicadoValue = clicado.html();
                clicado.html('<i class="fa fa-hourglass-half" style="font-size: 32px"></i>');
                console.log(data);


                $.ajax({
                    type: "POST",
                    url: action,
                    data: data,
                    success: function(ret) {
                        retArr = ret.split(",")
                        //clicado.html(retArr[1]);
                        //clicado.attr("data-set", retArr[0]);
                        clicado.html('<i class="fa fa-check text-green" style="font-size: 32px"></i>');
                        setTimeout(function(){clicado.html(clicadoValue); }, 2000);
                    },
                    error: function() {
                        clicado.html('<i class="fa fa-times text-red" style="font-size: 32px"></i>');
                        alert("Ocorreu um erro ao salvar os dados.");
                        setTimeout(function(){clicado.html(clicadoValue); }, 2000);
                    }
                });
            });


            $('#btnDeleteDocument').click(function(){
                $('.modal-body').html($(this).attr("modalBody"));
                $('.modal-title').html($(this).attr("modalTitle"));
                cid = $(this).attr("cid");
                pid = $(this).attr("pid");
                action = "{{ route('clients.providers.detach') }}?_token={{ csrf_token() }}";
                        //alert($(this).attr("fileId"));

            });

            $('#deleteConfirm').click(function(){
                        //$('.modal-body').append("Confirmar a exclusão deste arquivo"+ $(this).attr("file"));
                    //alert(fileId);
                    $('#confirmationModal').modal('hide');
                    var data= {cid:cid, pid: pid};
                    console.log(data);
                    console.log(action);

                    jQuery.ajax({
                        type: "POST",
                        url: action,
                        data: data,
                        success: function(data) {

                            if (data == 1) {
                                // console.log(data);
                                location.reload();

                            }else{
                                alert('Empresa não encontrada')
                            }
                        }
                    });
                });

        }
        saveActions();

	});


</script>


@endsection
@endsection
