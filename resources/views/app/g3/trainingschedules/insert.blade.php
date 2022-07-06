
@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')
<h1>{{__('general.Training')}} - {{__('general.add')}}</h1>
@stop



@section('content')
<div class="col-md-12">

	<div class="box box-primary">


		<form id="add_form" method="post" role="form" action="">
			@csrf
			<div class="box-body">



				<div class="form-group">
					<label>{{__('general.Name')}}</label>
					<input type="" name="name" id="name" class="form-control" placeholder="{{__('general.Input a text...')}}" value="">
				</div>
				
				<div class="form-group">
					<label>{{__('general.Init')}}</label>
					<input type="" name="dt_ini" id="dt_ini" class="form-control" placeholder="DD/MM/AAAA HH:MM" value="">
				</div>
				<div class="form-group">
					<label>{{__('general.End')}}</label>
					<input type="" name="dt_end" id="dt_end" class="form-control" placeholder="DD/MM/AAAA HH:MM" value="">
				</div>
				<div class="form-group">
					<label>URL</label>
					<input type="" name="url" id="url" class="form-control" placeholder="{{__('general.Input a text...')}}" value="">
				</div>
				<div class="form-group">
					<label>{{__('general.Test')}}</label>
					<select  name="test_id" id="test_id" class="form-control select2-responsive" >
                        <option value="">{{__('general.Select')}}</option>
                        @foreach ($tests as $test)
                        <option value="{{$test->id}}">{{$test->name}}</option>
                        @endforeach
                    </select>
				</div>
                @can('master')
				<div class="form-group">
					<label>{{__('general.Company')}}</label>
					<select  name="company" id="company" class="form-control" >
                        <option value="">{{__('general.Select')}}</option>
                        @foreach ($companies as $company)
                        <option value="{{$company->id}}">{{$company->name}}</option>
                        @endforeach
                    </select>
				</div>
                @else
                <input type="hidden" name="company" id="company" value="{{Auth::User()->company_id}}">
                @endcan
				<div class="form-group">
					<label>{{__('general.Vacancies')}}</label>
					<input type="number" name="vacancies" id="vacancies" class="form-control" placeholder="{{__('general.Input a text...')}}" value="">
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

	$('#dt_ini').mask('00/00/0000 00:00', {reverse: false});
	$('#dt_end').mask('00/00/0000 00:00', {reverse: false});


});
</script>

<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

@endsection

@endsection


<script src="/js/jquery_2.1.3_jquery.min.js"></script>
<script src="/js/twitter-bootstrap_3.3.1_js_bootstrap.min.js"></script>

<!-- Laravel Javascript Validation -->

{!! $validator->selector('#add_form') !!}
