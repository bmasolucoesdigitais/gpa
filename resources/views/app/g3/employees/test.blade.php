@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')
<h1>Employees</h1>
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


<a href="/employees/add" class="btn btn-app pull-right">
	<i class="fa fa-plus-square"></i> Add
</a>

<div class="col-xs-12">
	<div class="box">
		<div class="box-header">
			<h3 class="box-title">Employees</h3>


			<div class="box-tools">
				<div class="input-group input-group-sm" style="width: 150px;">
					<input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

					<div class="input-group-btn">
						<button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
					</div>
				</div>
			</div>
		</div>
		<!-- /.box-header -->
		<div class="box-body table-responsive no-padding">
			<table class="table table-hover">
				<tbody><tr>

					
					<th>id</th>
					<th>name</th>
					<th>cpf</th>
					<th>rg</th>
					<th>borndate</th>
					<th>allowed</th>
					<th>edit</th>
					<th>delete</th>
				</tr>
				@foreach ($employees as $employee)

				<tr>

					<td>{{ $employee->id}} - </td>
					<td>{{ $employee->name}}</td>
					<td>{{ $employee->cpf}}</td>
					<td>{{ $employee->rg}}</td>
					<td>{{ $employee->borndate}}</td>
					<td>{{ $employee->allowed}}</td>
					<td>
						<a class="	" href="edit/{{ $employee->id }}">

							<i class="fa fa-pencil-square" style="font-size: 32px"></i>

						</a>
					</td>
					<td> <a href="delete/{{ $employee->id }}">

						<i class="fa fa-trash-o text-red " style="font-size: 32px"></i>


					</a></td>
				</tr>
					@foreach ($employee->companies as $company)
							<tr>
					<td colspan="8">{{ $company->name}} - </td>
				</tr>
				@endforeach
				@endforeach

			</tbody></table>
		</div>
		<!-- /.box-body -->
	</div>
	<!-- /.box -->
</div>
@stop
