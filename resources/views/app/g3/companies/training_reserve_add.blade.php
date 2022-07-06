
@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')

	<h1>{{$training->name}} - {{__('general.add')}}</h1>

@stop



@section('content')
<div class="col-md-12">

	<div class="box box-primary">


		<form method="post" role="form" action="">
			@csrf
			<div class="box-body">

				<label>{{__('general.Employee')}}</label>

				<select   name="employee_id"  class="select2-responsive form-control" >
                   @foreach($employees as $employee)
                   <option value="{{$employee->id}}">{{$employee->name}} - {{$employee->cpf}}</option>
                   @endforeach
                  </select>
				  <div class="form-group">
					<label>{{__('general.E-Mail')}}</label>
					<input type="" name="email" id="email" class="form-control" placeholder="{{__('general.Input a text...')}}" value="">
				</div>



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
