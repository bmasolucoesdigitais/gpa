@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')
@if(Request::is('g3/branches*'))
<h1>{{__('general.Branches')}} - {{ $branch->name }} - {{__('general.Outsourceds')}}</h1>
@elseif(Auth::User()->can('master'))
<h1>{{__('general.Peoples')}}</h1>
@else
<h1>{{__('general.Outsourceds')}}</h1>
@endif


<!-- metodo de entrada para translations {{ __('employees.test') }} -->
@stop

@section('content')


<i class="fa  fa-check text-green " style="font-size: 32px"></i> Documentos validos
<i class="fa  fa-check text-yellow " style="font-size: 32px"></i> Autorização manual
@canany('master', 'Admin', 'G3 Edit')
@if(Request::is('g3/branches*'))
	<a href="{{route('branches.outsourceds.attach', $branch->id)}}" class="btn btn-app pull-right datatable">
		<i class="fa fa-plus-square"></i> {{__('general.Add')}}
	</a>

	<a href="{{route('branches')}}" class="btn btn-app pull-right">
		<i class="fa fa-arrow-left"></i> {{__('general.Branches')}}
	</a>

@else
<a href="{{route('employees.attach')}}" class="btn btn-app pull-right">
	<i class="fa fa-plus-square"></i> {{__('general.Add')}}
</a>
@endif
@endcanany


