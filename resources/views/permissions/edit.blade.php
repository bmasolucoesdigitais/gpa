@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')
    <h1><i class='fa fa-key'></i> Add Permission</h1>
@stop

@section('content')

<div class='col-lg-4 col-lg-offset-4'>

    <br>

    <form method="post" role="form" action="/permissions/update/{{$permission->id}}">
    @csrf
    <div class="form-group">
       <div class="form-group">
        <label>name</label>
        <input type="text" name="name" class="form-control"  value="{{$permission->name}}">
    </div>
    </div><br>

    <div class='form-group'>
       <label>Roles:</label>
           
    <br>
    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>

</div>

@endsection