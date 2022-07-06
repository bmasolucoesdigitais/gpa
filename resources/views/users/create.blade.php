
@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')
<h1><i class='fa fa-user-plus'></i> {{__('general.Add')}} {{__('general.User')}}</h1>
@stop

@section('content')


<div class='col-lg-4 col-lg-offset-4'>

    
    <hr>

@if(count($errors))
         <ul class="alert alert-danger">
             @foreach($errors->all() as $error)
                <li>{{$error}}</li>
              @endforeach
         </ul>
@endif


    <form method="POST" role="form" action="/users/store" id="my-form">
    @csrf
    <div class="form-group">
        <label>{{__('general.Name')}}</label>
        <input type="text" name="name" class="form-control" placeholder="Digite um texto..." value="">
    </div>

    <div class="form-group">
        <label>E-Mail</label>
        <input type="email" name="email" class="form-control" placeholder="Digite um texto..." value="">
    </div>
    @if($companies)
<div class="form-group">
                <label>{{__('general.Company')}}</label>
                    <select  name="company_id"  class="select2-responsive form-control" >
                   @foreach($companies as $company)
                   <option value="{{$company->id}}">{{$company->name}}</option>
                   @endforeach
                  </select>
                 </div> 
                 @else

                 <input type="hidden" name="company_id" class="form-control" value="{{Auth::user()->company_id}}">
                  @endif
    <div class='form-group'>

       <label>{{__('general.Roles')}}:</label>
        @foreach ($roles as $role)

        <div class="checkbox">
                    <label>
                      <input type="checkbox" name="roles[]" value="{{$role->id}}">
                       {{ $role->name}}
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

@section('js')
<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
@endsection


@endsection
<script src="/js/jquery_2.1.3_jquery.min.js"></script>

<!-- Laravel Javascript Validation -->

{!! $validator->selector('#my-form') !!}
