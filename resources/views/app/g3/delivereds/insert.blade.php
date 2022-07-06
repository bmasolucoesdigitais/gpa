
@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')
<h1>Delivereds Add</h1>
@stop



@section('content')
<div class="col-md-12">

	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">Editar </h3>
		</div>

		<form method="post" role="form" action="{{route('delivereds.create')}}">
			@csrf

<div class="box-body">

{{-- <div class="form-group">
	<label>{{__('general.Deliver date')}}</label>
	<input type="" name="description" class="form-control" placeholder="dd/mm/aaaa" value="">
</div> --}}


<div class="form-group">
	<label>{{__('general.Expires')}}</label>
	<input type="" id="expiration" name="expiration" class="form-control" placeholder="dd/mm/aaaa" value="">
</div>

<div class="form-group">
	<label>{{__('general.Document')}}</label>
	<select  name="document_id" class="select2-responsive form-control" >

		@foreach($data['documents'] as $document)
		<option value="{{$document->id}}">{{$document->name}}</option>
		@endforeach
	</select>
</div>
<div class="form-group">
	<label>{{__('general.Employee')}}</label>
	<select  name="employee_id" class="select2-responsive form-control" >

		@foreach($data['employees'] as $employee)
		<option value="{{$employee->id}}">{{$employee->name}}</option>
		@endforeach
	</select>
</div>

<div class="form-group">
	<label>{{__('general.Company')}}</label>
	<select  name="company_id" class="select2-responsive form-control" >

		@foreach($data['companies'] as $company)
		<option value="{{$company->id}}">{{$company->name}}</option>
		@endforeach
	</select>
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

	$('#expiration').mask('00/00/0000', {reverse: true});
	$('#description').mask('00/00/0000', {reverse: true});

});
</script>


@endsection
@endsection
