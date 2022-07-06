@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')
<h1><i class='fa fa-lock'></i> Add Role</h1>
@stop

@section('content')

<div class='col-lg-4 col-lg-offset-4'>

       


         <form method="post" role="form" action="/roles/store">
    @csrf
    <div class="form-group">
        <label>name</label>
        <input type="text" name="name" class="form-control" placeholder="Digite um texto..." value="">
    </div>

    
    <div class='form-group'>
       <label>Permissions:</label>
                    
         @foreach ($permissions as $permission)

        <div class="checkbox">
            <label> 
                      <input type="checkbox" name="permissions[]" value="{{ $permission->id}}">
                        {{$permission->name}}
                    </label>
                  </div>
          

        @endforeach
    </div>

   
    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>

</form>
</div>

@endsection