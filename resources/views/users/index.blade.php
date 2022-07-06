
@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')
<h1><i class="fa fa-users"></i> User Administration </h1>
@stop

@section('content')

<div class="col-lg-10 col-lg-offset-1">
    <a href="{{ route('roles.index') }}" class="btn btn-default pull-right">Roles</a>
    <a href="{{ route('permissions.index') }}" class="btn btn-default pull-right">Permissions</a></h1>
    <hr>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">

            <thead>
                <tr>
                    <th>{{__('general.Name')}}</th>
                    <th>E-mail</th>
                    <th>{{__('general.Company')}}</th>
                    <th>{{__('general.Date')}} {{__('general.Added')}}</th>
                    <th>{{__('general.User')}} {{__('general.Roles')}}</th>
                    <th>{{__('general.Edit')}}/{{__('general.delete')}}</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($users as $user)
                <tr>

                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->company->name }}</td>
                    <td>{{ $user->created_at->format('d/m/Y H:i') }}h</td>
                    <td>{{  $user->roles()->pluck('name')->implode(', ') }}</td>{{-- Retrieve array of roles associated to a user and convert to string --}}
                    <td>
                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-info pull-left" style="margin-right: 3px;">Edit</a>
                    <a class="btn btn-danger" href="destroy/{{ $user->id }}">Delete</a>

                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>

    <a href="{{ route('users.create') }}" class="btn btn-success">Add User</a>

</div>

@endsection