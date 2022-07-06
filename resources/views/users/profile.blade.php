
@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')
<h1><h1><i class='fa fa-user-plus'></i> {{__('Edit')}} {{$user->name}}</h1></h1>
@stop

@section('content')

<div class='col-lg-4 col-lg-offset-4'>

  <form method="post" role="form" action="">
    @csrf
    <div class="form-group">
        <label>{{__('general.Name')}}</label>
        <input type="text" name="name" class="form-control" value="{{$user->name}}">
    </div>

    <div class="form-group">
        <label>E-Mail</label>
        <input type="email" name="email" class="form-control" value="{{$user->email}}">
    </div>
 
    <div class="form-group">
       
        <label>{{__('general.Password')}}</label>
        <input type="password" name="password" class="form-control" placeholder="Digite um texto..." value="">

    </div>

    <div class="form-group">
        <label>{{__('general.Password Confirmation')}} </label>
        <input type="password" name="password_confirmation" class="form-control" placeholder="Digite um texto..." value="">

    </div>

    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>

</form>

</div>

@endsection