@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')
    <h1><i class='fa fa-key'></i> Add Permission</h1>
@stop

@section('content')

<div class='col-lg-4 col-lg-offset-4'>

    <br>

    <form method="post" role="form" action="/permissions/store">
    @csrf
    <div class="form-group">
       <div class="form-group">
        <label>name</label>
        <input type="text" name="name" class="form-control" placeholder="Digite um texto..." value="">
    </div>
    </div><br>
    @if(!$roles->isEmpty()) 
        <h4>Assign Permission to Roles</h4>

         @foreach ($roles as $role)

        <div class="checkbox">
                    <label>
                      <input type="checkbox" name="roles[]" value="{{$role->id}}">
                        {{$role->name}}
                    </label>
                  </div>
          

        @endforeach
    @endif
    <br>
    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>

</div>

@endsection