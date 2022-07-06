
@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')
<h1>{{__('general.Employees')}} - {{$data['employee']->name}} - {{$data['document']->name}} - {{__('general.Delivered')}} - {{__('general.Add')}}</h1>
@stop



@section('content')
<div class="col-md-12">

	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">{{__('general.Add')}} </h3>
		</div>

		<form id="add_form" method="post" role="form" action="" enctype="multipart/form-data">
			@csrf

<div class="box-body">

{{-- <div class="form-group">
	<label>{{__('general.Deliver date')}}</label>
	<input type="" id="description" name="description" class="form-control" placeholder="dd/mm/aaaa" value="">
</div> --}}


<div class="form-group">
	<label>{{__('general.Expires')}}</label>
	<input type="" id="expiration" name="expiration" class="form-control" placeholder="dd/mm/aaaa" value="">
</div>


<div class="form-group">
	<label>{{__('general.File')}}</label>
	<input type="file" accept=".jpg, .jpeg, .pdf, .png" id="file" name="file" class="form-control" value="">
</div>



@can('master')

                <div class="form-group">
						<label>{{__('general.Company')}}</label>


						<select   name="company"  class="select2-responsive form-control" >
                            <option value="" selected>{{__('general.Select')}}...</option>
                            <option value="1" >{{__('general.General')}}</option>
                   @foreach($clients as $client)
                   <option value="{{$client->id}}">{{$client->name}} - {{$client->cnpj}}</option>
                   @endforeach
                  </select>
					</div>
@else
<div class="form-group">

	<input type="hidden" name="company" class="form-control"  value="{{$data['document']->company_id}}">
</div>

@endcan
@can('fornecedor')
    <input type="hidden" name="status" value="2">
@else
<div class="form-group">
    <label>{{__('general.Analyze')}}</label>
    <select   name="status"  class="select2-responsive form-control" >
        <option value="0"  selected>{{ __('general.Normal') }}</option>
        <option value="1" >{{ __('general.Waiting correction') }}</option>
    </select>
</div>
@endcan
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

	$('#expiration').mask('00/00/0000', {reverse: false});
	$('#description').mask('00/00/0000', {reverse: false});

});
</script>

<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

@endsection

@endsection


<script src="/js/jquery_2.1.3_jquery.min.js"></script>
<script src="/js/twitter-bootstrap_3.3.1_js_bootstrap.min.js"></script>

<!-- Laravel Javascript Validation -->

{!! $validator->selector('#add_form') !!}
