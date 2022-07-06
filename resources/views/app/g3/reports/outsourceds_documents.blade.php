@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')

<h1>{{ __('general.Reports')}} - {{__('general.Outsourceds')}}</h1>

@stop

@section('content')




<div class="col-xs-12">
    <div class="box">
        
        <div class="box-body table-responsive ">
            <table id="datatable" class="table table-hover">
                <thead>
                    
                    <tr>
                        
                        <th>{{__('general.Company')}}</th>
                        <th>{{__('general.Name')}}</th>
                        <th>{{__('general.Document')}}</th>
                        <th>{{__('general.Expiration')}}</th>
                        <th>{{__('general.Status')}}</th>
                        <th>{{__('general.Analyze')}}</th>
                        
                        
                        
                    </tr>
                </thead>
                
                <tbody>
                    
                    
                    @foreach($companies as $company)
                        @foreach($company->employees->where('fl_deleted', 0) as $outsourced)
                            @foreach($outsourced->documents as $document)
                                @php
                                    $delivereds = $document->delivereds()->where('employee_id', $outsourced->id)->orderBy('id', 'desc')->take(1)->get();
                                @endphp
                                @if (count($delivereds)<1)
                                    <tr>
                                        @if($outsourced->companies()->count() > 0)
                                        <td>{{$outsourced->companies()->first()->name}}</td>
                                        @else
                                        <td>- Não Informado</td>
                                        @endif
                                        <td>{{$outsourced->name}}</td>
                                        <td>{{$document->name}}</td>
                                        <td  class="text-red">{{__('general.Not delivered')}}</td>
                                        <td  class="text-red">{{__('general.Not delivered')}}</td>
                                        <td  class="text-red">{{__('general.Not delivered')}}</td>
                                    </tr>
                                @else
                                    @foreach ($delivereds as $delivered)                                
                                        <tr>
                                            @if($outsourced->companies()->count() > 0)
                                            <td>{{$outsourced->companies()->first()->name}}</td>
                                            @else
                                            <td>- Não Informado</td>
                                            @endif
                                            <td>{{$outsourced->name}}</td>
                                            <td>{{$document->name}}</td>
                                            <td>{{$delivered->expiration}}</td>
                                            @if (preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$1$2$3', $delivered->expiration) < date('Ymd'))
                                            <td  class="text-red"> {{__('general.Expired')}}</td>
                                            @else
                                            <td class="text-green"> {{__('general.Valid')}}</td>
                                            @endif

                                                @if($delivered->status == 0 or $delivered->status == '')
                                                    <td class="text-green">
                                                        {{ __('general.Normal') }}
                                                    </td>
                                                @elseif($delivered->status == 1)
                                                    <td class="text-yellow">
                                                        {{ __('general.Waiting correction') }}
                                                    </td>
                                                @elseif($delivered->status == 2)
                                                    <td class="text-red">
                                                        {{ __('general.Waiting for approval') }}
                                                    </td>
                
                                                @endif
                                        </tr>
                                    @endforeach
                                @endif
                            
                            @endforeach
                        
                            @foreach($outsourced->services as $service)
                                @foreach($service->documents as $document)
                                    @php
                                        $delivereds = $document->delivereds()->where('employee_id', $outsourced->id)->orderBy('id', 'desc')->take(1)->get();
                                    @endphp
                                    @if (count($delivereds)<1)
                                        <tr>
                                            @if($outsourced->companies()->count() > 0)
                                            <td>{{$outsourced->companies()->first()->name}}</td>
                                            @else
                                            <td>- Não Informado</td>
                                            @endif
                                            <td>{{$outsourced->name}}</td>
                                            <td>{{$document->name}}</td>
                                            <td  class="text-red">{{__('general.Not delivered')}}</td>
                                            <td  class="text-red">{{__('general.Not delivered')}}</td>
                                            <td  class="text-red">{{__('general.Not delivered')}}</td>
                                        </tr>
                                    @else
                                        @foreach ($delivereds as $delivered)                                
                                           {{--  @if (preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$1$2$3', $delivered->expiration) < date('Ymd')) --}}
                                            
                                                <tr>
                                                    @if($outsourced->companies()->count() > 0)
                                                    <td>{{$outsourced->companies()->first()->name}}</td>
                                                    @else
                                                    <td>- Não Informado</td>
                                                    @endif
                                                    <td>{{$outsourced->name}}</td>
                                                    <td>{{$document->name}}</td>
                                                    <td>{{$delivered->expiration}}</td>
                                                    @if (preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$1$2$3', $delivered->expiration) < date('Ymd'))
                                                        <td  class="text-red"> {{__('general.Expired')}}</td>
                                                    @else
                                                        <td class="text-green"> {{__('general.Valid')}}</td>
                                                    @endif

                                                        @if($delivered->status == 0 or $delivered->status == '')
                                                            <td class="text-green">
                                                                {{ __('general.Normal') }}
                                                            </td>
                                                        @elseif($delivered->status == 1)
                                                            <td class="text-yellow">
                                                                {{ __('general.Waiting correction') }}
                                                            </td>
                                                        @elseif($delivered->status == 2)
                                                            <td class="text-red">
                                                                {{ __('general.Waiting for approval') }}
                                                            </td>
                        
                                                        @endif
                                                </tr>
                                            
                                           {{--  @endif --}}
                                        @endforeach
                                    @endif
                                
                                @endforeach
                            @endforeach
                        @endforeach
                    @endforeach
                    
                    
                    
                </tbody></table>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    @section('js')
    <script type="text/javascript">
        
        $( document ).ready(function() {
            $('#datatable').DataTable( {
                
                searchPanes:{
                    cascadePanes: true,
                    layout: 'columns-3'
                },
                
                dom: 'PBfrtipl',
                
                
                columnDefs:[
                {
                    searchPanes:{
                        show: false,
                    },
                    
                    targets: [3],
                },
                ],
                
                buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                "paging": true,           
                language: 
                {
                    
                    "sEmptyTable": "Nenhum registro encontrado",
                    "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                    "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                    "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                    "sInfoPostFix": "",
                    "sInfoThousands": ".",
                    "sLengthMenu": "_MENU_ resultados por página",
                    "sLoadingRecords": "Carregando...",
                    "sProcessing": "Processando...",
                    "sZeroRecords": "Nenhum registro encontrado",
                    "sSearch": "Pesquisar",
                    "oPaginate": {
                        "sNext": "Próximo",
                        "sPrevious": "Anterior",
                        "sFirst": "Primeiro",
                        "sLast": "Último"
                    },
                    "oAria": {
                        "sSortAscending": ": Ordenar colunas de forma ascendente",
                        "sSortDescending": ": Ordenar colunas de forma descendente"
                    },
                    "select": {
                        "rows": {
                            "_": "Selecionado %d linhas",
                            "0": "Nenhuma linha selecionada",
                            "1": "Selecionado 1 linha"
                        }
                    },
                    "buttons": {
                        "print":"Imprimir",
                        "copy": "Copiar",
                        "clearAll": "Limpar",
                        
                    },
                    searchPanes: {
                        title:{
                            _: 'Filtros selecionados - %d',
                            0: 'Nenhum filtro selecionado',
                            1: 'Um filtro selecionado',
                        },
                        clearMessage: "Limpar filtros",
                        
                    },
                },
                "initComplete": function(settings, json) {
                    $('div.dataTables_filter input').focus();
                }
                
            });
            
            
            
        });
    </script>
    
    
    @endsection
    @endsection
    