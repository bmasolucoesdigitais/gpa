
@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')
<h1>{{ __('general.Scheduled services') }} - {{__('general.add')}}</h1>
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
				@can('master')
				
				<div class="form-group">
					<label>{{__('general.Company')}}</label>
					
					<select   name="company_id"  id="company_id" class="form-control select2-responsive"  >
						<option value="">{{__('general.Select').' '.__('general.company')}}</option>
						@foreach($companies as $company)
						<option value="{{$company->id}}">{{$company->name}} - {{$company->cnpj}}</option>
						@endforeach
					</select>
				</div>
				@endcan	
				<div class="form-group">
					<label>{{__('general.Service')}}</label>
					<input type="" name="service" id="service" class="form-control" placeholder="{{__('general.Input a text...')}}" value="">
				</div>
				<div class="form-group">
					<label>{{__('general.Initial date')}}</label>
					<input type="" name="date_ini" id="date_ini" class="form-control" placeholder="{{__('general.datemask')}}" value="">
				</div>
				
				<div class="form-group">
					<label>{{__('general.Final date')}}</label>
					<input type="" name="date_end" id="date_end" class="form-control" placeholder="{{__('general.datemask')}}" value="">
				</div>
				
				<div class="form-group">
					
					<label>Ficha de liberação de trabalho de terceiros:</label><a  href="/images/fltt.doc"  class="btn btn-default"><i class="fa fa-download"  data-toggle="tooltip" data-original-title="Baixar Modelo"></i></a>
					<input type="file" name="file" id="file" class="form-control" placeholder="{{__('general.Input a text...')}}" value="">
				</div>
				
				
				<div class="form-group">
					<label>{{__('general.Employees')}} (Pode ser selecionado mais de um)</label>
					
					<select   name="employees[]"  id="employees" class="form-control select2-responsive" multiple >
						<option value="">{{__('general.Select').' '.__('general.employees')}}</option>
						@foreach($employees as $employee)
						<option value="{{$employee->id}}">{{$employee->name}} - {{$employee->cpf}}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<label>{{__('general.Store')}}</label>
					
					<select   name="store"  id="store" class="form-control select2-responsive" >
						<option value="">{{__('general.Select one').' '.__('general.store')}}</option>
						@foreach($stores as $store)
						<option value="{{$store->id}}">{{$store->name}} - {{$store->flag}}</option>
						@endforeach
					</select>
				</div>
				{{-- <div class="form-group">
					<label>{{__('general.Store')}} </label>
					<input type="" name="store" id="store" class="form-control" placeholder="{{__('general.Input a text...')}}" value="">
				</div> --}}
				
				
				
				
				@can('master')
				<div class="form-group">
					
					<label>Abaco {{__('general.Status')}}</label>
					<select class="form-control select2-responsive" name="aproved" id="aproved">
						<option value="0" >{{__('general.Waiting for approval')}}</option>
						<option value="1">{{__('general.Waiting correction')}}</option>
						<option value="2">Documentos expirados</option>
						<option value="3">Aprovado parcial</option>
						<option value="4">{{__('general.Aproved')}}</option>
					</select>
					
				</div>
				@endcan
				
				{{-- <input type="hidden" name="company_id" id="company_id" class="form-control"  value="1"> --}}
				
				
				<div class="box-footer">
					<button type="submit" class="btn btn-primary">{{__('general.Save')}}</button>
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
@can('master')
	
<script type="text/javascript">
	
	$( document ).ready(function() {
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
@endcan

<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

@endsection

@endsection


<script src="/js/jquery_2.1.3_jquery.min.js"></script>
<script src="/js/twitter-bootstrap_3.3.1_js_bootstrap.min.js"></script>

<!-- Laravel Javascript Validation -->

{!! $validator->selector('#add_form') !!}
