@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')
<h1>{{__('general.Employee')}} - {{$employee->name}} - {{__('general.services')}} - {{__('general.add')}}</h1>
<!-- metodo de entrada para translations {{ __('employees.test') }} -->
@stop

@section('content')



@if (session('message'))
<div class="alert alert-success">
	{{ session('message') }}
</div>
@endif

@if (session('alert-success'))
<div class="alert alert-success">
	{{ session('alert-success') }}
</div>
@endif



@section('content')

<div class="col-md-12">

	<div class="box box-primary">
	
		
		<form method="post" role="form" action="">
			@csrf

<div class="box-body">


<div class="form-group">
                <label>{{__('general.Services')}}</label>
	               	<select multiple="" name="services[]" size="10" class="select2-responsive form-control" >
                   @foreach($services as $service)
                   <option value="{{$service->id}}" @if($employee->Services()->find($service->id)) selected @endif>{{$service->name}}</option>
                   @endforeach
                  </select>
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
});
</script>
@endsection
@endsection
