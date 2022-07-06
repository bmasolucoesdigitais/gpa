@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')
<h1>{{__('general.Services')}} - {{$service->name}} - {{__('general.Documents')}} - {{__('general.edit')}}</h1>
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
		<div class="box-header with-border">
			<h3 class="box-title">{{$service->name}} documents add</h3>
		</div>
		
		<form method="post" role="form" action="">
			@csrf

<div class="box-body">


<div class="form-group">
                <label>Documnts</label>
	               	<select multiple="multiple" name="documents[]" size="10" class="select2-responsive form-control" >
                   @foreach($documents as $document)
                   <option value="{{$document->id}}" @if($service->Documents()->find($document->id)) selected @endif>{{$document->name}} - {{$document->description}}</option>
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
});
</script>
@endsection
@endsection
