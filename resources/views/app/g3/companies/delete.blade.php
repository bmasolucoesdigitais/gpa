
@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')
<h1>Company Delete</h1>
@stop



@section('content')
<div class="col-md-12">

		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Editar </h3>
			</div>
			<form method="post" role="form" action="{{route('companies.destroy')}}">
				 @csrf
				<div class="box-body">

					<div class="form-group">
                    <label>Title</label>
                    <h4>
                    	Are you sure thats want to delete the company:<br/>
                    	{{$company->name}}
                    </h4>
                  </div>

					<input type="hidden" name="id" value="{{$company->id}}">

				</div>
				<!-- /.box-body -->

				<div class="box-footer">
					<button type="submit" class="btn btn-primary">Delete</button>
				</div>
			</form>
		</div>
		


	</div>






	@stop