@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')
<h1>{{__('general.Settings')}} - {{__('general.Documents')}}</h1>
<!-- metodo de entrada para translations {{ __('documents.test') }} -->
@stop

@section('content')

<div class="col-xs-12">

<div class="box box-primary">


		<form method="post" role="form" action="">
			@csrf
			<div class="box-body">

				<label>{{__('general.Company')}}</label>

				<select   name="company"  class="select2-responsive form-control" >
                   @foreach($companies as $company)
                   <option value="{{$company->id}}" @if($selected->id == $company->id) selected @endif>{{$company->name}}</option>
                   @endforeach
                  </select>



					<div class="box-footer">
						<button type="submit" class="btn btn-primary">{{__('general.Select')}}</button>
					</div>
				</div>

		</div>
    </form>
    @if($documents != '')
	<div class="box">
        *{{__('general.Coma separeted values')}}
		<div class="box-body table-responsive ">
			<table id="datatable" class="table table-hover">
				<thead>

					<tr>


						<th>{{__('general.Name')}}</th>
						<th>{{__('general.Client')}}</th>
						<th>{{__('general.Provider')}}</th>
						<th>Ábaco</th>
					{{-- 	<th>+ {{__('general.Client')}}*</th>
						<th>+ Ábaco*</th>
						<th>{{__('general.Save')}}</th> --}}

					</tr>
				</thead>
				<tbody>
					@foreach ($documents as $document)

					@php $cpsets = $document->docsettings()->where('company_id', $selected->id)->first();@endphp
					<tr>
						<td>{{ $document->name}}</td>
						<td>
							<a href="" class="change" data-set="{{$cpsets['id']}}" data-cp="{{$selected->id}}" data-doc="{{$document->id}}" data-type="fl_client">
								{{$cpsets['fl_client']==1?1:0}}
							</a>
						</td>
						<td>
							<a href="" class="change" data-set="{{$cpsets['id']}}" data-cp="{{$selected->id}}" data-doc="{{$document->id}}" data-type="fl_provider">
								{{$cpsets['fl_provider']==1?1:0}}
							</a>
						</td>
						<td>
							<a href="" class="change" data-set="{{$cpsets['id']}}" data-cp="{{$selected->id}}" data-doc="{{$document->id}}" data-type="fl_abaco">
								{{$cpsets['fl_abaco']==1?1:0}}
							</a>
						</td>
						{{-- <td>
                                <input id="client_{{ $document->id }}" type="text" value="{{ $cpsets['aditional_client'] }}">

						</td>

						<td>
								<input id="abaco_{{ $document->id }}" type="text" value="{{ $cpsets['aditional_abaco'] }}">
                        </td>
                        <td>
                            <a class="save" href="{{ $document->id }}" data-cp="{{$selected->id}}" data-doc="{{$document->id}}">
                                <i class="fa fa-save" style="font-size: 32px"></i>
                            </a>
                        </td> --}}

					</tr>

					@endforeach

				</tbody></table>
			</div>
			<!-- /.box-body -->
		</div>
		@endif
		<!-- /.box -->
	</div>

	@section('js')
<script type="text/javascript">

$( document ).ready(function() {
	$('#datatable').DataTable( {
		"paging": false,
		"initComplete": function(settings, json) {
			$('div.dataTables_filter input').focus();
  		}
	});

	$('.change').click(function(event){
		event.preventDefault();
		var cp = $(this).attr("data-cp");
		var doc = $(this).attr("data-doc");
		var id = $(this).attr("data-set");
		var type = $(this).attr("data-type");
		var data= {'id':id, 'cp':cp, 'doc':doc, 'type': type  };
		var action = 'documents_alerts_change?_token={{ csrf_token() }}';
		var clicado = $(this);
		console.log(data);


		$.ajax({
			type: "POST",
			url: action,
			data: data,
			success: function(ret) {
				retArr = ret.split(",")
				clicado.html(retArr[1]);
				clicado.attr("data-set", retArr[0]);
				if (data == 1) {
					 console.log(ret);
					//location.reload();

				}else{
					console.log(ret);
				}
			}
		});



	});

    $('.save').click( function(event){
		event.preventDefault();
		console.log($(this).attr('href'));
		console.log($('#client_'+$(this).attr('href')).val());

		var cp = $(this).attr("data-cp");
		var doc = $(this).attr("data-doc");
		var client = $('#client_'+$(this).attr('href')).val()
		var abaco = $('#abaco_'+$(this).attr('href')).val()
		var data= {'client':client, 'abaco':abaco, 'cp':cp, 'doc':doc };
        var action = 'documents_aditional_save?_token={{ csrf_token() }}';
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
			}
		});


	});
});
</script>


@endsection
@endsection
