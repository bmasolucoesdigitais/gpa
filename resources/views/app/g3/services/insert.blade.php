
@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')
<h1>{{__('general.Service')}} - {{__('general.Add')}}</h1>
@stop



@section('content')
<div class="col-md-12">

	<div class="box box-primary">
		
		
		<form id="add_form" method="post" role="form" action="{{route('services.create')}}">
			@csrf

<div class="box-body">		

<div  class="form-group">
	<label>{{__('general.Name')}}</label>
	<input type="" name="name" class="form-control" placeholder="Digite um texto..." value="">
</div>
<div class="form-group">
	<label>{{__('general.Description')}}</label>
	<input type="" name="description" class="form-control" placeholder="Digite um texto..." value="">
</div>
<div class="form-group">
	<label>{{__('general.Initials')}}</label>
	<input type="" name="initials" class="form-control" placeholder="Digite um texto..." value="">
</div>
@can('master')

<div class="form-group">
						<label>{{__('general.Company')}}</label>
						

						<select   name="company_id"  class="select2-responsive form-control" >
                   @foreach($companies as $company)
                   <option value="{{$company->id}}">{{$company->name}} - {{$company->cnpj}}</option>
                   @endforeach
                  </select>
					</div>
@else
<div class="form-group">
	
	<input type="hidden" name="company_id" class="form-control" " value="{{Auth()->User()->company_id}}">
</div>

@endcan


				<div class="box-footer">
					<button type="submit" class="btn btn-primary">{{__('general.Save')}}</button>
				</div>
		</div>
	</form>


	</div>

@section('js')
<script type="text/javascript">

$( document ).ready(function() {
	$(".select2-responsive").select2({
	    width: 'resolve' // need to override the changed default
	});

});
</script>

<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

@endsection

@endsection


<script src="/js/jquery_2.1.3_jquery.min.js"></script>
<script src="/js/twitter-bootstrap_3.3.1_js_bootstrap.min.js"></script>

<!-- Laravel Javascript Validation -->

{!! $validator->selector('#add_form') !!}