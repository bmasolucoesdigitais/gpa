@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')
<h1>{{__('general.Company')}} - {{__('general.add')}}</h1>
@stop



@section('content')
<div class="col-md-12">

	<div class="box box-primary">
		
		
		<form method="post" role="form" action="">
			@csrf
			<input type="hidden" name="id" id="id" class="form-control"  value="{{$id}}">
			<div class="box-body">


				<div class="form-group">
					<label>{{__('general.cnpj')}}</label>
					<input type="" name="cnpj" id="cnpj" class="form-control" placeholder="{{__('general.Input a text...')}}" value="">
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

	$('#cnpj').mask('00.000.000/0000-00', {reverse: true});
	

});
</script>


@endsection
@endsection