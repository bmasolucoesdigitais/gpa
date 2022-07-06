
@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')

@if(Request::is('g3/branches'))
	<h1>{{__('general.Branches')}} - {{$company->name}} - {{__('general.Outsourceds')}} - {{__('general.add')}}</h1>
@else
	<h1>{{__('general.Company')}} - {{$company->name}} - {{__('general.Outsourceds')}} - {{__('general.add')}}</h1>
@endif

@stop



@section('content')
<div class="col-md-12">

	<div class="box box-primary">
		
		
		<form method="post" role="form" action="">
			@csrf
			<div class="box-body">

				<label>{{__('general.Employee')}}</label>
				
				<select   name="employee"  class="select2-responsive form-control" >
                   @foreach($employees as $employee)
                   <option value="{{$employee->id}}">{{$employee->name}} - {{$employee->cpf}}</option>
                   @endforeach
                  </select>
				


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
});
</script>
@endsection
@endsection