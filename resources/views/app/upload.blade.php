@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')
<h1>{{__('general.Upload')}}</h1>
@stop



@section('content')
<form action="{{route('upload')}}" method="post" enctype="multipart/form-data">
	@csrf
    <input type="file" accept=".jpg, .jpeg, .pdf, .png" name="image" >
    <input type="hidden" name="path" value="{{Auth::User()->company_id}}">
  <button type="submit" class="btn btn-primary">{{__('general.Save')}}</button>
</form>

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


@endsection
@endsection