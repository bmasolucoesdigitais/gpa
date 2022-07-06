
@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')
<h1>{{__('general.Company')}} - {{$company->name}} - {{__('general.edit')}}</h1>
@stop



@section('content')
<div class="col-md-12">

	<div class="box box-primary">


		<form id="add_form" method="post" role="form" action="">
			@csrf
			<div class="box-body">


				<div class="form-group">
					<label>{{__('general.cnpj')}}</label>
					<input type="" name="cnpj" id="cnpj" class="form-control" placeholder="" value="{{$company->cnpj}}">
				</div>
				<div class="form-group">
					<label>{{__('general.Name')}}</label>
					<input type="" name="name" id="name" class="form-control" placeholder="" value="{{$company->name}}">
				</div>
				<div class="form-group">
					<label>Filial</label>
					<input type="" name="filial" id="filial" class="form-control" placeholder="" value="{{$company->filial}}">
				</div>
				<div class="form-group">
					<label>Bandeira</label>
					<input type="" name="flag" id="flag" class="form-control" placeholder="" value="{{$company->flag}}">
				</div>
				<div class="form-group">
					<label>{{__('general.Client emails')}} ( {{__('general.Coma separeted values')}} )</label>
					<input type="" name="manager_email" id="manager_email" class="form-control" placeholder="" value="{{$company->manager_email}}">
				</div>
				<div class="form-group">
					<label>{{__('general.Abaco emails')}} ( {{__('general.Coma separeted values')}} )</label>
					<input type="" name="abaco_email" id="abaco_email" class="form-control" placeholder="" value="{{$company->abaco_email}}">
				</div>
				<div class="form-group">
					<label>{{__('general.Address')}}</label>
					<input type="" name="address" id="address" class="form-control" placeholder="" value="{{$company->address}}">
				</div>
				<div class="form-group">
					<label>{{__('general.Number')}}</label>
					<input type="" name="number" id="number" class="form-control" placeholder="" value="{{$company->number}}">
				</div>
				<div class="form-group">
					<label>{{__('general.Complement')}}</label>
					<input type="" name="complement" id="complement" class="form-control" placeholder="" value="{{$company->complement}}">
				</div>
				<div class="form-group">
					<label>{{__('general.Neighborhood')}}</label>
					<input type="" name="neighborhood" id="neighborhood" class="form-control" placeholder="" value="{{$company->neighborhood}}">
				</div>
				<div class="form-group">
					<label>{{__('general.CEP')}}</label>
					<input type="" name="cep" id="cep" class="form-control" placeholder="" value="{{$company->cep}}">
				</div>
				<div class="form-group">
					<label>{{__('general.Citie')}}</label>
					<input type="" name="citie" id="citie" class="form-control" placeholder="" value="{{$company->citie}}">
				</div>
				<div class="form-group">
					<label>{{__('general.State')}}</label>
					<input type="" name="state" id="state" class="form-control" placeholder="" value="{{$company->state}}">
				</div>
				<div class="form-group">
					<label>{{__('general.Country')}}</label>
					<input type="" name="country" id="country" class="form-control" placeholder="" value="{{$company->country}}">
				</div>
				<div class="form-group">
					<label>{{__('general.Password')}}</label>
					<input type="password" name="password" id="password" class="form-control" placeholder="" value="">
				</div>
				@can('master')

				<div class="form-group">
                <label>{{__('general.Headquarter')}}</label>
	               	<select  name="headquarter" class="select2-responsive form-control" >
                   <option value="" >{{ __('general.No one') }}</option>
                   @foreach($headquarters as $headquarter)
                   <option value="{{$headquarter->id}}" @if($company->headquarter == $headquarter->id) selected @endif>{{$headquarter->name}}</option>
                   @endforeach
                  </select>
     			 </div>

				<div class="form-group col-md-12">

					<div class="checkbox col-md-3">
					<label>
							<input type="checkbox" name="fl_aprove" id="fl_aprove"   @if ($company->fl_aprove === 1) checked @endif  value="1">{{__('general.Aprove')}}</label>
					</div>

					<div class="checkbox col-md-3">
					<label>
						<input type="checkbox" name="fl_active" id="fl_active"  @if ($company->fl_active === 1) checked  @endif value="1">{{__('general.Active')}}</label>
					</div>

					<div class="checkbox col-md-3">
					<label>
						<input type="checkbox" name="fl_billing" id="fl_billing"  @if ($company->fl_billing === 1) checked  @endif  value="1">{{__('general.Billing')}}</label>
					</div>

					<div class="checkbox col-md-3">
					<label>
						<input type="checkbox" name="fl_client" id="fl_client"  @if ($company->fl_client === 1) checked  @endif  value="1">{{__('general.Client')}}</label>
					</div>
				</div>
				@endcan
				<div class="form-group">

					<input type="hidden" name="company_id" id="company_id" class="form-control" placeholder="" value="{{$company->company_id}}">
				</div>


				<div class="box-footer">
					<button type="submit" class="btn btn-primary">Save</button>
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

	$('#cnpj').mask('00.000.000/0000-00', {reverse: true});
	$('#cep').mask('00000-000', {reverse: true});
	$('#state').mask('AA', {'translation': {
	    A: {pattern: /[A-Za-z]/},
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
