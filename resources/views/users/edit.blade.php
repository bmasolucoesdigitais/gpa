
@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')
<h1><h1><i class='fa fa-user-plus'></i> {{__('Edit')}} {{$user->name}}</h1></h1>
@stop

@section('content')

<div class='col-lg-4 col-lg-offset-4'>

  <form method="post" role="form" action="/users/update/{{$user->id}}">
    @csrf
    <div class="form-group">
        <label>{{__('general.Name')}}</label>
        <input type="text" name="name" class="form-control" value="{{$user->name}}">
    </div>

    <div class="form-group">
        <label>E-Mail</label>
        <input type="email" name="email" class="form-control" value="{{$user->email}}">
    </div>
    @if($companies)
    <div class="form-group">
                <label>{{__('general.Company')}}</label>
                    <select  name="company_id"  class="select2-responsive form-control" >
                   @foreach($companies as $company)
                   <option value="{{$company->id}}" @if($user->company_id == $company->id) selected @endif>{{$company->filial}} - {{$company->name}}</option>
                   @endforeach
                  </select>
    </div>
    @else
         <input type="hidden" name="company_id" class="form-control" value="{{$user->company_id}}">
    @endif
    <div class='form-group'>
       <label>{{__('general.Roles')}}:</label>
          @foreach ($roles as $role)
       <div class="checkbox">
                    <label>
                      <input type="checkbox" name="roles[]" value= "{{$role->id}}" @if($user->hasRole($role->name)) checked @endif>
                        {{$role->name}}
                    </label>
                  </div>
           

        @endforeach
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