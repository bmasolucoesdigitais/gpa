
@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')
<h1>{{__('general.Outsourced')}} - {{__('general.add')}}</h1>
@stop



@section('content')
<div class="col-md-12">

	<div class="box box-primary">
		
		
		<form method="post" role="form" action="">
			@csrf
			<div class="box-body">


				<div class="form-group">
					<label>{{__('general.cpf')}}</label>
					<input type="" name="cpf" id="cpf" class="form-control" placeholder="{{__('general.Input a text...')}}" value="">
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

	$('#cpf').mask('000.000.000-00', {reverse: true});
	

});
</script>


@endsection
@endsection