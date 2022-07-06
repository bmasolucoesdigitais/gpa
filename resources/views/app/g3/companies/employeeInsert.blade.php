
@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')
<h1>{{__('general.Employee')}} - {{__('general.add')}}</h1>
@stop



@section('content')
<div class="col-md-12">

	<div class="box box-primary">
		
		
		<form id="add_form" method="post" role="form" action="">
			@csrf

<div class="box-body">


				

<div class="form-group">
	<label>{{__('general.Name')}}</label>
	<input type="" name="name" class="form-control" placeholder="Digite um texto..." value="">
</div>
<div class="form-group">
	<label>{{__('general.cpf')}}</label>
	<input type="" id='cpf' name="cpf" class="form-control" placeholder="Digite um texto..." value="">
</div>
<div class="form-group">
	<label>{{__('general.rg')}}</label>
	<input type="" id='rg' name="rg" class="form-control" placeholder="Digite um texto..." value="">
</div>
<div class="form-group">
	<label>{{__('general.Borndate')}}</label>
	<input type="" id="borndate" name="borndate" class="form-control" placeholder="Digite um texto..." value="">
</div>
<div class="form-group">
		<input type="checkbox" name="allowed"  value="1">
		{{__('general.Allowed')}}
	
</div>




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

	/*$('#rg').mask('000.000.000-A', 
		{
			translation: {'A': {pattern: /[A-Za-z0-9]/}},
      		reverse: true
		});*/
	$('#cpf').mask('000.000.000-00', {reverse: true});
	$('#borndate').mask('00/00/0000', {reverse: true});
	
});
</script>
<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

@endsection

@endsection


<script src="/js/jquery_2.1.3_jquery.min.js"></script>
<script src="/js/twitter-bootstrap_3.3.1_js_bootstrap.min.js"></script>

<!-- Laravel Javascript Validation -->

{!! $validator->selector('#add_form') !!}