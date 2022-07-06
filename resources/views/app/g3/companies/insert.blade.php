
@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')
<h1>{{__('general.Company')}} - {{__('general.add')}}</h1>
@stop



@section('content')
<div class="col-md-12">

	<div class="box box-primary">


		<form id="add_form" method="post" role="form" action="">
			@csrf
			<div class="box-body">


				<div class="form-group">
					<label>{{__('general.cnpj')}}</label>
					<input type="" name="cnpj" id="cnpj" class="form-control" placeholder="{{__('general.Input a text...')}}" @if($cnpj)value="{{$cnpj}}"@esle value=""@endif>
				</div>
				<div class="form-group">
					<label>{{__('general.Name')}}</label>
					<input type="" name="name" id="name" class="form-control" placeholder="{{__('general.Input a text...')}}" value="">
				</div>
				<div class="form-group">
					<label>Filial</label>
					<input type="" name="filial" id="filial" class="form-control" placeholder="{{__('general.Input a text...')}}" value="">
				</div>
				<div class="form-group">
					<label>Bandeira</label>
					<input type="" name="flag" id="flag" class="form-control" placeholder="{{__('general.Input a text...')}}" value="">
				</div>
				<div class="form-group">
					<label>{{__('general.Client emails')}} ( {{__('general.Coma separeted values')}} )</label>
					<input type="" name="manager_email" id="manager_email" class="form-control" placeholder="{{__('general.Input a text...')}}" value="">
				</div>
				<div class="form-group">
					<label>{{__('general.Provider emails')}} ( {{__('general.Coma separeted values')}} )</label>
					<input type="" name="company_email" id="company_email" class="form-control" placeholder="{{__('general.Input a text...')}}" value="">
				</div>
				<div class="form-group">
					<label>{{__('general.Abaco emails')}} ( {{__('general.Coma separeted values')}} )</label>
					<input type="" name="abaco_email" id="abaco_email" class="form-control" placeholder="{{__('general.Input a text...')}}" value="">
				</div>
				<div class="form-group">
					<label>{{__('general.Address')}}</label>
					<input type="" name="address" id="address" class="form-control" placeholder="{{__('general.Input a text...')}}" value="">
				</div>
				<div class="form-group">
					<label>{{__('general.Number')}}</label>
					<input type="" name="number" id="number" class="form-control" placeholder="{{__('general.Input a text...')}}" value="">
				</div>
				<div class="form-group">
					<label>{{__('general.Complement')}}</label>
					<input type="" name="complement" id="complement" class="form-control" placeholder="{{__('general.Input a text...')}}" value="">
				</div>
				<div class="form-group">
					<label>{{__('general.Neighborhood')}}</label>
					<input type="" name="neighborhood" id="neighborhood" class="form-control" placeholder="{{__('general.Input a text...')}}" value="">
				</div>
				<div class="form-group">
					<label>{{__('general.CEP')}}</label>
					<input type="" name="cep" id="cep" class="form-control" placeholder="{{__('general.Input a text...')}}" value="">
				</div>
				<div class="form-group">
					<label>{{__('general.Citie')}}</label>
					<input type="" name="citie" id="citie" class="form-control" placeholder="{{__('general.Input a text...')}}" value="">
				</div>
				<div class="form-group">
					<label>{{__('general.State')}}</label>
					<input type="" name="state" id="state" class="form-control" placeholder="{{__('general.Input a text...')}}" value="">
				</div>
				<div class="form-group">
					<label>{{__('general.Country')}}</label>
					<input type="" name="country" id="country" class="form-control" placeholder="{{__('general.Input a text...')}}" value="">
				</div>
				<div class="form-group">
					<label>{{__('general.Password')}}</label>
					<input type="password" name="password" id="password" class="form-control" placeholder="{{__('general.Input a text...')}}" value="">
				</div>
				<div class="form-group col-md-12">

					<div class="col-md-3">
					<label>
							<input type="checkbox" name="fl_aprove" id="fl_aprove"   value="1"> {{__('general.Aprove')}}</label>
					</div>

					<div class="col-md-3">
					<label>
						<input type="checkbox" name="fl_active" id="fl_active" checked value="1"> {{__('general.Active')}}</label>
					</div>

					<div class="col-md-3">
					<label>
						<input type="checkbox" name="fl_billing" id="fl_billing"  checked value="1"> {{__('general.Billing')}}</label>
					</div>

					<div class="col-md-3">
					<label>
						<input type="checkbox" name="fl_client" id="fl_client"  value="1"> {{__('general.Client')}}</label>
					</div>
				</div>

					<input type="hidden" name="company_id" id="company_id" class="form-control"  value="1">


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

	$('#cnpj').mask('00.000.000/0000-00', {reverse: true});
	$('#cep').mask('00000-000', {reverse: true});
	$('#number').mask('0000000', {reverse: true});
	$('#state').mask('AA', {'translation': {
	    A: {pattern: /[A-Z]/},
	  }
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
