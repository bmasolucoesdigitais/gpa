
@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')
<h1>{{ __('general.Scheduled services') }} - APR - Análise Preliminar de Risco</h1>
@stop

@section('content')

<a href="{{route('companies.servicesscheduled.list')}}" class="btn btn-app pull-right no-print">
	<i class="fa fa-arrow-left"></i> {{__('general.Scheduled services')}}
</a>
<button  onclick="window.print();" class="btn btn-app pull-right no-print">
	<i class="fa fa-print"></i> {{__('general.Print')}}
</button>

@can('G3 Admin')
{{-- <div>
	<form action="">
		<select name="status" id="fl_status">
			<option value="0" @if ($apr->fl_status == 0)	selected @endif>Aguardando aprovação</option>
			<option value="2" @if ($apr->fl_status == 1) selected @endif>Aguardando corrreção</option>
			<option value="3" @if ($apr->fl_status == 2) selected @endif>Aprovado</option>
		</select>
		<span id="status"></span>
	</form>
</div> --}}
@endcan

<div class="col-md-12">
	
	<div class="box box-primary">
		<table width="100%">
			<tr>
				<td colspan="3" class="bg-grey">
					APR - Análise Preliminar de Risco
				</td>
			</tr>
			
			<tr>
				<td>
					{{__('general.Excutor')}}
				</td>
				<td colspan="2">
					{{$service->company->name}}
				</td>
			</tr>
			<tr>
				<td>
					{{__('general.Contracted service')}}
				</td>
				<td colspan="2">
					{{$service->service}}
				</td>
			</tr>
			<tr>
				<td>
					{{__('general.Maker')}}
				</td>
				<td>
					{{$apr->maker}}
				</td>
				<td>
					{{__('general.Elaboration date')}}: {{date('d/m/Y')}}
				</td>
			</tr>
			
			
		</table>
		
		
		<table style="width:100%">
			<tr class="bg-grey">
				<th> No</th>
				<th> Atividade</th>
				<th> Fonte de risco</th>
				<th> Fatores de risco</th>
				<th> Consequências</th>
				<th> Medidas de controle</th>
			</tr>
			
			@php
			
			$countitems = 1;
			@endphp
			@foreach($apr->items as $item)
			<tr>
				
				<td> {{$countitems}}</td>
				<td> {{$item->activity}}</td>
				<td> {{$item->risk_source}}</td>
				<td> {{$item->risk_factor}}</td>
				<td> {{$item->consequence}}</td>
				<td> {{$item->action}}</td>
				
				
			</tr>
			@php
			$countitems ++;
			@endphp
			@endforeach
			
			
		</table>
		
		
		
		
		
		<div class="form-group">
			<table style="width: 100%">
				<tr class="bg-grey">
					<th colspan="3" class="text-center"><h4>Assinatura dos envolvidos</h4></th>
				</tr>
				<tr>
					<th>Nome</th>
					<th>RG</th>
					<th>Assinatura</th>
				</tr>
				
				@foreach($service->employees as $employee)
				<tr>
					
					<td>{{$employee->name}}</td>
					<td>{{$employee->rg}}</td>
					<td width="30%"></td>
				</tr>
				@endforeach
			</table>
		</div>
		
		<div class="form-group">
			<table>
				<tr>
					<td>
						Assinatura do executante: 
					</td>
				</tr>
				<tr>
					<td>
						{{__('general.Valid')}}: {{ preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$3/$2/$1', $service->date_ini) }}
						{{__('general.until')}} {{ preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$3/$2/$1', $service->date_end) }} </h4> 
					</td>
				</tr>
				<tr>
					<td>
						<p>
							Glossário:
						</p>
						<p>
							<b> Fonte de Risco:</b> São fontes, situações ou atos com potencial para provocar danos a pessoas, ao patrimônio, ao processo e/ou ao meio-ambiente. (representado por um substantivo)
						</p>
						<p>		
							<b>Fatores de Riscos:</b> São condições ou situações que favorecem a ocorrência do cenário de perdas aumentando a probabilidade da ocorrência ou a dimensão dos impactos das perdas.
						</p>
						<p>		
							Consequências: É o descritivo qualitativo e quantitativo da abrangência ou dimensão das perdas; (se possível calcular o valor financeiro da perda e registrar como memória - comentário
						</p>
					</td>
				</tr>
			</table>
			
		</div>
		
		
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
					<h4>{{__('general.Observations')}}:</h4>
					<p >{{$apr->observation}}</p>
				</div>
			</div>
			<div class="col-xs-12">
				
				{{-- <div class="box-footer">
					<button id="btn_send" type="submit" class="btn btn-primary">{{__('general.Save')}}</button>
				</div> --}}
			</div>
		</div>
		
	</div>
</form>
</div>
</div>
<style>
	@media print {
		.noPrint{
			display:none;
		}
		.row{
			border: 1px solid black;
		}
		table{
			width:100%;
		}
		table, th, td{
			border: 1px solid black;
			line-height: 30px;
		}
		.bg-grey{
			background-color: #ddd !important;
			text-transform: uppercase;
			text-align: center;
		}
		td, th{
			padding:3px;
		}
		th{
			text-align:center;
		}
	}
	@media  {
		.noPrint{
			display:none;
		}
		.row{
			border: 1px solid black;
		}
		table{
			width:100%;
		}
		table, th, td{
			border: 1px solid black;
			line-height: 30px;
		}
		.bg-grey{
			background-color: #eee !important;
			text-transform: uppercase;
			text-align: center;
		}
		td, th{
			padding:3px;
		}
		th{
			text-align:center;
		}
	}
</style>
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

@can('master')
@endcan
<script type="text/javascript">
	
	$( document ).ready(function() {
		
/* 
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
			
		}) */
		
		
		
		$('#fl_status').change(function(){
			$('#status').html("Aguarde!");
			//$('this').attr('disabled') = true;
			status = $(this).val();
			id = {{$apr->id}},
			$.ajax({	
				type: "get",
				url: "/g3/companies/servicesscheduled/apr/"+id+"/chnagestatus/"+status,
				success: function (response) {
					if(response == 1){
						$('#status').html("Salvo!");

					}else{
						$('#status').html("Erro!");
					}
					
				}
			});
	});
});
</script>


<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
<script type="text/javascript" >
	
	
	
</script>

@endsection

@endsection


<script src="/js/jquery_2.1.3_jquery.min.js"></script>
<script src="/js/twitter-bootstrap_3.3.1_js_bootstrap.min.js"></script>

<!-- Laravel Javascript Validation -->

{{-- {!! $validator->selector('#add_form') !!} --}}
