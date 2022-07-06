
@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')
<h1>{{ __('general.Scheduled services') }} - APR - Análise Preliminar de Risco</h1>
@stop

@section('content')

<a href="{{route('companies.servicesscheduled.list')}}" class="btn btn-app pull-right">
    <i class="fa fa-arrow-left"></i> {{__('general.Companies')}}
</a>


<div class="col-md-12">

	<div class="box box-primary">


		<form id="add_form" method="post" role="form" action="" enctype="multipart/form-data">
			@csrf
			<div class="box-body">

				{{-- @can('master')
				
				<div class="form-group">
					<label>{{__('general.Company')}}</label>
					
					<select   name="company_id"  id="company_id" class="form-control select2-responsive"  >
						<option value="">{{__('general.Select').' '.__('general.company')}}</option>
						@foreach($companies as $company)
						<option value="{{$company->id}}" @if($service->company_id == $company->id) selected @endif>{{$company->name}} - {{$company->cnpj}}</option>
						@endforeach
					</select>
				</div>
				@endcan	 --}}

				<div class="form-group">
					<label></label>
					<h4 >{{__('general.Excutor')}}: {{$service->company->name}}</h4>
				</div>
				
				<div class="form-group">
					<label></label>
					<h4 >{{__('general.Contracted service')}}: {{$service->service}}</h4>
				</div>
				
				<div class="form-group">
					<label></label>
					<h4 >{{__('general.Elaboration date')}}: {{date('d/m/Y')}}</h4>
				</div>

				<div class="form-group">
					<label></label>
					<h4 >
						{{__('general.Valid')}}: {{ preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$3/$2/$1', $service->date_ini) }}
						{{__('general.until')}} {{ preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$3/$2/$1', $service->date_end) }} </h4>
				</div>

				<div class="form-group">
					<label>{{__('general.Employees')}}</label>

						<ul>

							@foreach($service->employees as $employee)
							<li >{{$employee->name}} - {{$employee->cpf}}</li>
							@endforeach
						</ul>
				</div>
				<div class="form-group">
					<label></label>
					<h4 >{{__('general.Elaboration date')}}: {{date('d/m/Y')}}</h4>
				</div>
				<div class="form-group">
					<label>{{__('general.Maker')}}</label>
						<input class="form-control" type="text" name="maker" value="" >
				</div>
				
				<div id="activities-header col-sm-12">
					<div class="col-xs-1">
						<h4>No</h4>
					</div>
					<div class="col-xs-2">
						<h4>Atividade</h4>
					</div>
					<div class="col-xs-2">
						<h4>Fonte de risco</h4>
					</div>
					<div class="col-xs-2">
						<h4>Fatores de risco</h4>
					</div>
					<div class="col-xs-2">
						<h4>Consequências</h4>
					</div>
					<div class="col-xs-2">
						<h4>Medidas de controle</h4>
					</div>
					<div class="col-xs-1">
						{{-- <h4>Ações</h4> --}}	
					</div>
				</div>
				<div class="col-xs-12 form-group">
					<div class="col-xs-1">
						<input class="form-control" type="text" value="1" disabled>
					</div>
					<div class="col-xs-2">
						<textarea rows="3" class="form-control" type="text" name="activity[1][activity]" value="" ></textarea>
					</div>
					<div class="col-xs-2">
						<textarea rows="3" class="form-control" type="text" name="activity[1][source]" value="" ></textarea>
					</div>
					<div class="col-xs-2">
						<textarea rows="3" class="form-control" type="text" name="activity[1][factor]" value="" ></textarea>
					</div>
					<div class="col-xs-2">
						<textarea rows="3" class="form-control" type="text" name="activity[1][cons]" value="" ></textarea>
					</div>
					<div class="col-xs-2">
						<textarea rows="3" class="form-control" type="text" name="activity[1][action]" value="" ></textarea>
					</div>
					<div class="col-xs-1">
						
					</div>
				</div>
				<div class="col-xs-12 form-group">
					<div class="col-xs-1">
						<input class="form-control" type="text" value="2" disabled>
					</div>
					<div class="col-xs-2">
						<textarea rows="3" class="form-control" type="text" name="activity[2][activity]" value="" ></textarea>
					</div>
					<div class="col-xs-2">
						<textarea rows="3" class="form-control" type="text" name="activity[2][source]" value="" ></textarea>
					</div>
					<div class="col-xs-2">
						<textarea rows="3" class="form-control" type="text" name="activity[2][factor]" value="" ></textarea>
					</div>
					<div class="col-xs-2">
						<textarea rows="3" class="form-control" type="text" name="activity[2][cons]" value="" ></textarea>
					</div>
					<div class="col-xs-2">
						<textarea rows="3" class="form-control" type="text" name="activity[2][action]" value="" ></textarea>
					</div>
					<div class="col-xs-1">
						
					</div>
				</div>
				<div class="col-xs-12 form-group">
					<div class="col-xs-1">
						<input class="form-control" type="text" value="3" disabled>
					</div>
					<div class="col-xs-2">
						<textarea rows="3" class="form-control" type="text" name="activity[3][activity]" value="" ></textarea>
					</div>
					<div class="col-xs-2">
						<textarea rows="3" class="form-control" type="text" name="activity[3][source]" value="" ></textarea>
					</div>
					<div class="col-xs-2">
						<textarea rows="3" class="form-control" type="text" name="activity[3][factor]" value="" ></textarea>
					</div>
					<div class="col-xs-2">
						<textarea rows="3" class="form-control" type="text" name="activity[3][cons]" value="" ></textarea>
					</div>
					<div class="col-xs-2">
						<textarea rows="3" class="form-control" type="text" name="activity[3][action]" value="" ></textarea>
					</div>
					<div class="col-xs-1">
						
					</div>
				</div>
				<div class="col-xs-12 form-group">
					<div class="col-xs-1">
						<input class="form-control" type="text" value="4" disabled>
					</div>
					<div class="col-xs-2">
						<textarea rows="3" class="form-control" type="text" name="activity[4][activity]" value="" ></textarea>
					</div>
					<div class="col-xs-2">
						<textarea rows="3" class="form-control" type="text" name="activity[4][source]" value="" ></textarea>
					</div>
					<div class="col-xs-2">
						<textarea rows="3" class="form-control" type="text" name="activity[4][factor]" value="" ></textarea>
					</div>
					<div class="col-xs-2">
						<textarea rows="3" class="form-control" type="text" name="activity[4][cons]" value="" ></textarea>
					</div>
					<div class="col-xs-2">
						<textarea rows="3" class="form-control" type="text" name="activity[4][action]" value="" ></textarea>
					</div>
					<div class="col-xs-1">
						
					</div>
				</div>
				<div class="col-xs-12 form-group">
					<div class="col-xs-1">
						<input class="form-control" type="text" value="5" disabled>
					</div>
					<div class="col-xs-2">
						<textarea rows="3" class="form-control" type="text" name="activity[5][activity]" value="" ></textarea>
					</div>
					<div class="col-xs-2">
						<textarea rows="3" class="form-control" type="text" name="activity[5][source]" value="" ></textarea>
					</div>
					<div class="col-xs-2">
						<textarea rows="3" class="form-control" type="text" name="activity[5][factor]" value="" ></textarea>
					</div>
					<div class="col-xs-2">
						<textarea rows="3" class="form-control" type="text" name="activity[5][cons]" value="" ></textarea>
					</div>
					<div class="col-xs-2">
						<textarea rows="3" class="form-control" type="text" name="activity[5][action]" value="" ></textarea>
					</div>
					<div class="col-xs-1">
						
					</div>
				</div>
				{{-- <div class="col-xs-12 form-group">
					<div class="col-xs-1">
						<input class="form-control" type="text" value="6" disabled>
					</div>
					<div class="col-xs-2">
						<textarea rows="3" class="form-control" type="text" name="activity[6][activity]" value="" ></textarea>
					</div>
					<div class="col-xs-2">
						<textarea rows="3" class="form-control" type="text" name="activity[6][source]" value="" ></textarea>
					</div>
					<div class="col-xs-2">
						<textarea rows="3" class="form-control" type="text" name="activity[6][factor]" value="" ></textarea>
					</div>
					<div class="col-xs-2">
						<textarea rows="3" class="form-control" type="text" name="activity[6][cons]" value="" ></textarea>
					</div>
					<div class="col-xs-2">
						<textarea rows="3" class="form-control" type="text" name="activity[6][action]" value="" ></textarea>
					</div>
					<div class="col-xs-1">
						
					</div>
				</div>
				<div class="col-xs-12 form-group">
					<div class="col-xs-1">
						<input class="form-control" type="text" value="7" disabled>
					</div>
					<div class="col-xs-2">
						<textarea rows="3" class="form-control" type="text" name="activity[7][activity]" value="" ></textarea>
					</div>
					<div class="col-xs-2">
						<textarea rows="3" class="form-control" type="text" name="activity[7][source]" value="" ></textarea>
					</div>
					<div class="col-xs-2">
						<textarea rows="3" class="form-control" type="text" name="activity[7][factor]" value="" ></textarea>
					</div>
					<div class="col-xs-2">
						<textarea rows="3" class="form-control" type="text" name="activity[7][cons]" value="" ></textarea>
					</div>
					<div class="col-xs-2">
						<textarea rows="3" class="form-control" type="text" name="activity[7][action]" value="" ></textarea>
					</div>
					<div class="col-xs-1">
						
					</div>
				</div>
				<div class="col-xs-12 form-group">
					<div class="col-xs-1">
						<input class="form-control" type="text" value="8" disabled>
					</div>
					<div class="col-xs-2">
						<textarea rows="3" class="form-control" type="text" name="activity[8][activity]" value="" ></textarea>
					</div>
					<div class="col-xs-2">
						<textarea rows="3" class="form-control" type="text" name="activity[8][source]" value="" ></textarea>
					</div>
					<div class="col-xs-2">
						<textarea rows="3" class="form-control" type="text" name="activity[8][factor]" value="" ></textarea>
					</div>
					<div class="col-xs-2">
						<textarea rows="3" class="form-control" type="text" name="activity[8][cons]" value="" ></textarea>
					</div>
					<div class="col-xs-2">
						<textarea rows="3" class="form-control" type="text" name="activity[8][action]" value="" ></textarea>
					</div>
					<div class="col-xs-1">
						
					</div>
				</div>
				<div class="col-xs-12 form-group">
					<div class="col-xs-1">
						<input class="form-control" type="text" value="9" disabled>
					</div>
					<div class="col-xs-2">
						<textarea rows="3" class="form-control" type="text" name="activity[9][activity]" value="" ></textarea>
					</div>
					<div class="col-xs-2">
						<textarea rows="3" class="form-control" type="text" name="activity[9][source]" value="" ></textarea>
					</div>
					<div class="col-xs-2">
						<textarea rows="3" class="form-control" type="text" name="activity[9][factor]" value="" ></textarea>
					</div>
					<div class="col-xs-2">
						<textarea rows="3" class="form-control" type="text" name="activity[9][cons]" value="" ></textarea>
					</div>
					<div class="col-xs-2">
						<textarea rows="3" class="form-control" type="text" name="activity[9][action]" value="" ></textarea>
					</div>
					<div class="col-xs-1">
						
					</div>
				</div>
				<div class="col-xs-12 form-group">
					<div class="col-xs-1">
						<input class="form-control" type="text" value="10" disabled>
					</div>
					<div class="col-xs-2">
						<textarea rows="3" class="form-control" type="text" name="activity[10][activity]" value="" ></textarea>
					</div>
					<div class="col-xs-2">
						<textarea rows="3" class="form-control" type="text" name="activity[10][source]" value="" ></textarea>
					</div>
					<div class="col-xs-2">
						<textarea rows="3" class="form-control" type="text" name="activity[10][factor]" value="" ></textarea>
					</div>
					<div class="col-xs-2">
						<textarea rows="3" class="form-control" type="text" name="activity[10][cons]" value="" ></textarea>
					</div>
					<div class="col-xs-2">
						<textarea rows="3" class="form-control" type="text" name="activity[10][action]" value="" ></textarea>
					</div>
					<div class="col-xs-1">
						
					</div>
				</div> --}}
			
				{{-- <div class="form-group">
					<label>{{__('general.Maker')}}</label>
					<input type="" name="maker" id="maker" class="form-control" placeholder="{{__('general.Input a text...')}}" value="">
				</div>
				<div class="form-group">
					<label>{{__('general.Initial date')}}</label>
					<input type="" name="date_ini" id="date_ini" class="form-control" placeholder="{{__('general.datemask')}}" value="{{ preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$3/$2/$1', $service->date_ini) }}">
				</div>
				
				<div class="form-group">
					<label>{{__('general.Final date')}}</label>
					<input type="" name="date_end" id="date_end" class="form-control" placeholder="{{__('general.datemask')}}" value="{{ preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$3/$2/$1', $service->date_end) }}">
				</div>

				<div class="form-group">
					<label>Ficha de liberação de trabalho de terceiros:</label>
					<input type="file" name="file" id="file" class="form-control" placeholder="{{__('general.Input a text...')}}" value="">
				</div>
 --}}
				
				
				{{-- <div class="form-group">
					<label>{{__('general.Store')}}</label>

						<select   name="store"  id="store" class="form-control select2-responsive" >
								<option value="">{{__('general.Select one').' '.__('general.store')}}</option>
							@foreach($stores as $store)
								<option value="{{$store->id}}" @if($service->store_id == $store->id) selected @endif>{{$store->name}} - {{$store->flag}}</option>
							@endforeach
						</select>
				</div> --}}
				{{-- <div class="form-group">
					<label>{{__('general.Store')}} </label>
					<input type="" name="store" id="store" class="form-control" placeholder="{{__('general.Input a text...')}}" value="">
				</div> --}}
				

				
{{-- 
				@can('master')
				<div class="form-group">

					<label>Abaco {{__('general.Status')}}</label>
						<select class="form-control select2-responsive" name="aproved" id="aproved">
							<option value="0" >{{__('general.Waiting for approval')}}</option>
							<option value="1">{{__('general.Waiting correction')}}</option>
							<option value="2">{{__('general.Aproved')}}</option>
						</select>

				</div>
				@endcan
 --}}
					{{-- <input type="hidden" name="company_id" id="company_id" class="form-control"  value="1"> --}}
					<div class="col-xs-12">
					<div class="form-group">
							<label>{{__('general.Observations')}}</label>
							<textarea rows="" class="form-control" type="text" name="observation" value="" ></textarea>
					</div>
				</div>
					<div class="col-xs-12">
							
						<div class="box-footer">
							<button id="btn_send" type="submit" class="btn btn-primary">{{__('general.Save')}}</button>
						</div>
					</div>
				</div>

		</div>
	</form>
</div>
</div>
@section('js')
<script type="text/javascript">

$( document ).ready(function() {
	$(".select2-responsive").select2({
	    width: 'resolve' // need to override the changed default
	});

	$('#date_ini').mask('00/00/0000', {reverse: true});
	$('#date_end').mask('00/00/0000', {reverse: true});


});
</script>

	
<script type="text/javascript">
	
	$( document ).ready(function() {

$('#btn_send').click(function(event){
	event.preventDefault();

	error = false;
	for(var i=1; i<=5; i++){
		if($("#activity"+i).val() != null && $("#activity"+i).val() != ''){
			console.log('verificando', i);
			console.log('Activity', i,':', $("#activity"+i).val());
			if($('#source'+i).val() == null || $('#source'+i).val() == ''){
				error = true;
			}
			if($('#factor'+i).val() == null || $('#factor'+i).val() == ''){
				error = true;
			}
			if($('#cons'+i).val() == null || $('#cons'+i).val() == ''){
				error = true;
			}
			if($('#action'+i).val() == null || $('#action'+i).val() == ''){
				error = true;
			}
		}
	}
	//console.log(error);
	if(error){
		alert('Caso a atividade seja preenchida os campos (Fonte de risco, Fator de risco, Consequências e Medidas de controle), da mesma linha são obrigatórios');
	}else{
		console.log('Enviando');
		$("#add_form").submit();
	}
	
})



$('#company_id').change(function(){
	id = $(this).val();
	$('#employees').prop('disabled',true);
	$.ajax({
		type: "get",
		url: "/g3/api/company/"+id+"/employees",
		success: function (response) {
			$('#employees').prop('disabled', false);
			items = JSON.parse(response);
			options = '';
			items.forEach(element => {
				options += '<option value="'+element.id+'">'+element.name+' - '+element.cpf+'</option>'; 
			});
			$('#employees').html(options);
		}
	});
});
});
</script>


<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

@endsection

@endsection


<script src="/js/jquery_2.1.3_jquery.min.js"></script>
<script src="/js/twitter-bootstrap_3.3.1_js_bootstrap.min.js"></script>

<!-- Laravel Javascript Validation -->

{{-- {!! $validator->selector('#add_form') !!} --}}
