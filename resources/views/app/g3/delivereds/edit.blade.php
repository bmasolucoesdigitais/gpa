
@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')
<div class="row">
	<div class="col-md-6">
		<h2>Editar entregas</h2>
	</div>
	<div class="col-md-6">
		<a class="btn btn-primary" style="float: right;margin-top:22px" href="{{ url("/g3/delivereds") }}">Voltar</a>
	</div>
</div>
@stop



@section('content')
<div class="row">
<div class="col-md-12">
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">Editar </h3>
		</div>
		
		<form method="post" role="form" action="{{route('delivereds.update', $delivered->id)}}">
			@csrf
			<div class="box-body">


<div class="form-group">
	<label>{{__('general.Deliver date')}}</label>
	<input type="" name="description" class="form-control" placeholder="Digite um texto..." value="{{$delivered->description}}">
</div>


<div class="form-group">
	<label>{{__('general.Expires')}}</label>
	<input type="" id="expiration" name="expiration" class="form-control" placeholder="Digite um texto..." value="{{ date('d/m/Y', strtotime($delivered->expiration))}}">
</div>

<div class="form-group">
	<label>{{__('general.Document')}}</label>
	<select  name="document_id" class="select2-responsive form-control" >

		@foreach($data['documents'] as $document)
		<option value="{{$document->id}}" @if( $document->id == $delivered->document_id) selected @endif>{{$document->name}}</option>
		@endforeach
	</select>
</div>
<div class="form-group">
	<label>{{__('general.Employee')}}</label>
	<select  name="employee_id" class="select2-responsive form-control" >

		@foreach($data['employees'] as $employee)
		<option value="{{$employee->id}}" @if( $employee->id == $delivered->employee_id) selected @endif>{{$employee->name}}</option>
		@endforeach
	</select>
</div>

<div class="form-group">
	<label>{{__('general.Company')}}</label>
	<select  name="company_id" class="select2-responsive form-control" >

		@foreach($data['companies'] as $company)
		<option value="{{$company->id}}" @if( $company->id == $delivered->company_id) selected @endif>{{$company->name}}</option>
		@endforeach
	</select>
</div>

				
				


				<div class="box-footer">
					<button type="submit" class="btn btn-success" style="float: right;">Editar</button>
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

	$('#expiration').mask('00/00/0000', {reverse: true});
	$('#description').mask('00/00/0000', {reverse: true});
	
});
</script>


@endsection
@endsection