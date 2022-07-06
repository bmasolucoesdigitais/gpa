@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')
@section('content_header')
@if (Request::is('g3/branches*'))
    <h1>{{ __('general.Branches') }} - {{ $branch->name }} - {{ __('general.Outsourceds') }} - {{ $employee->name }}</h1>
@else
    <h1>{{__('general.Employee')}}</h1>
@endif
<!-- metodo de entrada para translations {{ __('services.test') }} -->
@stop

@section('content')
<link href="{{ asset('css/cracha.css') }}" rel="stylesheet" type="text/css" media="print" />

@if (Request::is('g3/branches*'))
    {{-- expr --}}
<a href="{{route('branches.outsourceds', $branch->id)}}" class="btn btn-app pull-right">

<i class="fa fa-arrow-left"></i> {{__('general.Outsourceds')}}
</a>
    @else

<a href="{{ url()->previous() }}" class="btn btn-app pull-right">
    <i class="fa fa-arrow-left"></i> {{__('general.Back')}}
</a>
<a href="javascript:window.print();" class="pull-right  btn btn-app no-print"><i class="fa fa-print"></i>Imprimir</a>
@endif

<div class="col-md-12">
          <div class="box box-solid">
            <div class="box-header with-border">
              <h2 class="box-title">{{$employee->name}} - {{ $employee->cpf }}</h2>
            </div>

            @can('fornecedor')
            <h4 class="no-print">Terceiro em:</h4>
            <ul>
                @if (count($employee->outsourceds) <= 0)
                    <li class="callout callout-warning">
                                        <span > Não alocado </span>

                                </li>
                @endif
                    @foreach($employee->outsourceds as $outsourced)

                    <li class="callout callout-success> {{$outsourced->name}} - {{$outsourced->cnpj}}</span>

                    @endforeach
                </li></ul>
            @endcan

            <h4 class="no-print">Documentos</h4>
                <ul>
            @if (count($employee->documents) <= 0)
                        <li class="callout callout-warning">
                                            <span > Sem Documentos </span>

                                    </li>
                    @endif
                        @foreach($employee->documents as $document)

                            <!-- <li>{{$document->name}} - {{$document->description}}</li>-->

                                @php
                                    if (Auth::User()->can('master')){
                                         $delivered = $document->Delivereds()->orderBy('id', 'desc')->where('fl_deleted', 0)->where('employee_id', $employee->id)->first();
                                    }else{
                                        $delivered = $document->Delivereds()->where('fl_deleted', 0)->where('employee_id', $employee->id)->orderBy('id', 'desc')->first();
                                    }

                                @endphp
                                @if($delivered)
                                    @if(strtotime($delivered->expiration) > strtotime(Now()))
                                        @if($delivered->status == 0)
                                            <li class="callout callout-success @if($document->fl_print==0) no-print @endif" >{{$document->name}}<span class="no-print"> - {{$document->description}}</span> - Valido até:
                                                    <span >{{date('d/m/Y', strtotime($delivered->expiration))}} - {{ __('general.Normal') }}</span>
                                        @elseif($delivered->status == 1)
                                            <li class="callout callout-warning @if($document->fl_print==0) no-print @endif" >{{$document->name}}<span class="no-print"> - {{$document->description}}</span> - Valido até:
                                                <span >{{date('d/m/Y', strtotime($delivered->expiration))}} - {{ __('general.Waiting correction') }}</span>

                                        @elseif($delivered->status == 2)
                                            <li class="callout callout-warning @if($document->fl_print==0) no-print @endif" >{{$document->name}}<span class="no-print"> - {{$document->description}}</span> - Valido até:
                                                <span >{{date('d/m/Y', strtotime($delivered->expiration))}} - {{ __('general.Waiting for approval') }} </span>

                                        @endif
                                    @else
                                        <li class="callout callout-danger @if($document->fl_print==0) no-print @endif">{{$document->name}}<span class="no-print"> - {{$document->description}}</span> - Vencido em:
                                            <span >{{date('d/m/Y', strtotime($delivered->expiration))}}  - {{$delivered->status}} </span>
                                    @endif
                                        </li>
                                @else
                                     <li class="callout callout-danger" @if($document->fl_print==0) no-print @endif>
                                            <span > {{$document->name}}<span class="no-print"> - {{$document->description}}</span> - Não entregue </span>

                                    </li>
                                @endif

                        @endforeach
                    </li></ul>
            <!-- /.box-header -->
            <div class="box-body ">
                @cannot('fornecedor')
            	<div class="no-print"><h4>Empresas</h4>
            	@php

                    if (Auth::User()->can('master')) {
                        $companies = $employee->Companies()->where('fl_deleted', 0)->get();
                    }else{
                        $companies = $employee->Companies()->where('fl_deleted', 0)->get();
                        //$companies = $employee->Companies()->where('fl_deleted', 0)->whereIn('id', Auth::User()->company->clients->pluck('id'))->get();

                    }

                @endphp



                @if(count($companies)>0)
            	<ul>
            	@foreach ($companies  as $company)
            		<li>{{$company->name}}</li>
                    <ul>
                        @foreach($company->documents as $document)

                            <!--<li>{{$document->name}} - {{$document->description}}</li>-->

                                @php

                                    $delivered = $document->Delivereds()->where('fl_deleted', 0)->where('company_id', $company->id)->where('fl_deleted', 0)->orderBy('expiration', 'desc')->first()
                                @endphp
                                @if($delivered)
                                        @if(strtotime($delivered->expiration) > strtotime(Now()))

                                        {{-- @if($delivered->status == 0)
                                            <li class="callout callout-success @if($document->fl_print==0) no-print @endif" >{{$document->name}}<span class="no-print"> - {{$document->description}}</span> - Valido até:
                                            <span >{{date('d/m/Y', strtotime($delivered->expiration))}} - {{ __('general.Normal') }}</span>
                                        @elseif($delivered->status == 1)
                                            <li class="callout callout-warning @if($document->fl_print==0) no-print @endif" >{{$document->name}}<span class="no-print"> - {{$document->description}}</span> - Valido até:
                                                <span >{{date('d/m/Y', strtotime($delivered->expiration))}} - {{ __('general.Waiting correction') }}</span>

                                        @elseif($delivered->status == 2)
                                            <li class="callout callout-warning @if($document->fl_print==0) no-print @endif" >{{$document->name}}<span class="no-print"> - {{$document->description}}</span> - Valido até:
                                                <span >{{date('d/m/Y', strtotime($delivered->expiration))}} - {{ __('general.Waiting for approval') }} </span>

                                        @endif --}}
                                            <li class="callout callout-success @if($document->fl_print==0) no-print @endif">{{$document->name}} - {{$document->description}} - Valido até:
                                            <span >{{date('d/m/Y', strtotime($delivered->expiration))}} </span>
                                        @else
                                            <li class="callout callout-danger @if($document->fl_print==0) no-print @endif">{{$document->name}} - {{$document->description}} - Vencido em:
                                            <span >{{date('d/m/Y', strtotime($delivered->expiration))}} </span>
                                        @endif
                                    </li>
                                @else
                                     <li class="callout callout-danger @if($document->fl_print==0) no-print @endif">
                                            <span > {{$document->name}} - {{$document->description}} - Não entregue </span>

                                    </li>
                                @endif

                        @endforeach

                    </ul>
            	@endforeach
            	</ul>
                @else
                    Não é funcionário de nenhuma empresa!
                @endif
                @endcan
            	</div>
            		<h4 class="no-print">Serviços</h4>
            	<ul>

            	@foreach ($employee->Services()->where('fl_deleted', 0)->get() as $service)
            		<div class="">

                    <h4>{{$service->name}}</h4>
            		<ul>
            		@if (count($service->documents) <= 0)
                        <li class="callout callout-warning">
                                            <span > Sem Documentos </span>

                                    </li>
                    @endif
                    	@foreach($service->Documents()->where('fl_deleted', 0)->get() as $document)

            				<!-- <li>{{$document->name}} - {{$document->description}}</li> -->

                                @php

                                    $delivered = $document->Delivereds()->where('fl_deleted', 0)->where('fl_deleted', 0)->where('employee_id', $employee->id)->orderBy('id', 'desc')->first()
                                @endphp
                                @if($delivered)
                                    @if(strtotime($delivered->expiration) > strtotime(Now()))
                                    @if($delivered->status == 0)
                                        <li class="callout callout-success @if($document->fl_print==0) no-print @endif" >{{$document->name}}<span class="no-print"> - {{$document->description}}</span> - Valido até:
                                        <span >{{date('d/m/Y', strtotime($delivered->expiration))}} - {{ __('general.Normal') }}</span>
                                    @elseif($delivered->status == 1)
                                        <li class="callout callout-warning @if($document->fl_print==0) no-print @endif" >{{$document->name}}<span class="no-print"> - {{$document->description}}</span> - Valido até:
                                            <span >{{date('d/m/Y', strtotime($delivered->expiration))}} - {{ __('general.Waiting correction') }}</span>

                                    @elseif($delivered->status == 2)
                                        <li class="callout callout-warning @if($document->fl_print==0) no-print @endif" >{{$document->name}}<span class="no-print"> - {{$document->description}}</span> - Valido até:
                                            <span >{{date('d/m/Y', strtotime($delivered->expiration))}} - {{ __('general.Waiting for approval') }} </span>

                                    @endif
                                        {{-- <li class="callout callout-success @if($document->fl_print==0) no-print @endif">{{$document->name}}<span class="no-print"> - {{$document->description}}</span> - Valido até:
                                        <span >{{date('d/m/Y', strtotime($delivered->expiration))}} </span> --}}
                                    @else
                                    <li class="callout callout-danger @if($document->fl_print==0) no-print @endif">{{$document->name}}<span class="no-print"> - {{$document->description}}</span> - Vencido em:
                                            <span >{{date('d/m/Y', strtotime($delivered->expiration))}} </span>
                                        @endif
                                    </li>
                                @else
                                     <li class="callout callout-danger @if($document->fl_print==0) no-print @endif">
                                            <span > {{$document->name}}<span class="no-print"> - {{$document->description}}</span> - Não entregue </span>

                                    </li>
                                @endif

            			@endforeach
            		</ul>
                    </div>
            	@endforeach
            	</ul>

            <h4 class="no-print">Lojas</h4>
               <ul class="no-print">

                   @foreach ($outs as $store)
                   <li>- {{ $store->name }}</li>
                   @endforeach
                </ul>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
@section('js')
<script type="text/javascript">

$( document ).ready(function() {
$('#datatable').DataTable( {
 			"initComplete": function(settings, json) {
    			$('div.dataTables_filter input').focus();
  			}
		});
});
</script>


@endsection
@endsection
