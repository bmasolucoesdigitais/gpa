@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')

<h1>{{ __('general.Reports')}} - {{__('general.Outsourceds')}}</h1>

@stop

@section('content')




<div class="col-xs-12">
    <div class="box">
        <div class="box-body table-responsive ">

            <form action="#" id="report"  method="GET">
                <div class="form-group">
                    <label>Empresa</label>


                    <select name="company_id"  class="select2-responsive form-control" id="company_id">

                        <option value="0" @if($company_id == 0) selected @endif >Seleciona um:</option>
                        @foreach ($companies as $company)
                            <option value="{{ $company->id }}" @if($company_id == $company->id) selected @endif>{{ $company->name }}</option>
                        @endforeach
                    </select>
                </div>
                @if (isset($providers))

                <div class="form-group">
                    <label>Fornecedor</label>


                    <select name="provider_id" class="select2-responsive form-control" id="provider_id">

                        <option value="0" @if($provider_id == 0) selected @endif >Seleciona um:</option>
                        @foreach ($providers as $provider)
                        <option value="{{ $provider->id }}" @if($provider_id == $provider->id) selected @endif>{{ $provider->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
            </form>
            @isset($outsourceds)



            <table id="datatable" class="table table-hover">
                <thead>

                    <tr>

                        <th>{{__('general.Company')}}</th>
                        <th>{{__('general.Name')}}</th>
                        {{-- <th>{{__('general.Company')}}</th> --}}
                        <th>{{__('general.Document')}}</th>
                        <th>{{__('general.Expiration')}}</th>
                        <th>{{__('general.Status')}}</th>
                        <th>{{__('general.Analyze')}}</th>



                    </tr>
                </thead>

                <tbody>


                   {{--  @foreach($companies as $company)--}}
                    @foreach($outsourceds as $outsourced)
                    @foreach($outsourced->documents as $document)
                    @php
                        $delivereds = $document->delivereds()->where('fl_deleted', 0)->where('employee_id', $outsourced->id)->orderBy('id', 'desc')->take(1)->get();
                        @endphp
                        @if (count($delivereds)<1)
                        <tr>
                            <td>{{$cp->name}}</td>
                            <td>{{$outsourced->name}}</td>
                           {{--  @if($outsourced->companies()->count() > 0)
                            <td>{{$outsourced->companies()->first()->name}}</td>
                            @else
                            <td>- Não Informado</td>
                            @endif --}}
                            <td>{{$document->name}}</td>
                            <td  class="text-red">{{__('general.Not delivered')}}</td>
                            <td  class="text-red">{{__('general.Not delivered')}}</td>
                            <td  class="text-red">{{__('general.Not delivered')}}</td>
                        </tr>
                        @else
                        @foreach ($delivereds as $delivered)
                        <tr>
                            <td>{{$cp->name}}</td>
                            <td>{{$outsourced->name}}</td>
                           {{--  @if($outsourced->companies()->count() > 0)
                            <td>{{$outsourced->companies()->first()->name}}</td>
                            @else
                            <td>- Não Informado</td>
                            @endif --}}
                            <td>{{$document->name}}</td>
                            <td>{{$delivered->expiration}}</td>
                            @if (preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$1$2$3', $delivered->expiration) < date('Ymd'))
                            <td  class="text-red"> {{__('general.Expired')}}</td>
                            @else
                            <td class="text-green"> {{__('general.Valid')}}</td>
                            @endif
                            <td>

                                @if($delivered->status == 0 or $delivered->status == '')
                                    <span class="text-green">
                                        {{ __('general.Normal') }}
                                    </span>
                                @elseif($delivered->status == 1)
                                    <span class="text-yellow">
                                        {{ __('general.Waiting correction') }}
                                    </span>
                                @elseif($delivered->status == 2)
                                    <span class="text-red">
                                        {{ __('general.Waiting for approval') }}
                                    </span>

                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @endif

                        @endforeach

                        @foreach($outsourced->services as $service)
                        @foreach($service->documents as $document)
                        @php
                        $delivereds = $document->delivereds()->where('fl_deleted', 0)->where('employee_id', $outsourced->id)->orderBy('id', 'desc')->take(1)->get();
                        @endphp
                        @if (count($delivereds)<1)
                        <tr>
                            <td>{{$cp->name}}</td>
                            <td>{{$outsourced->name}}</td>
                           {{--  @if($outsourced->companies()->count() > 0)
                            <td>{{$outsourced->companies()->first()->name}}</td>
                            @else
                            <td>- Não Informado</td>
                            @endif --}}
                            <td>{{$document->name}}</td>
                            <td  class="text-red">{{__('general.Not delivered')}}</td>
                            <td  class="text-red">{{__('general.Not delivered')}}</td>
                            <td  class="text-red">{{__('general.Not delivered')}}</td>
                        </tr>
                        @else
                        @foreach ($delivereds as $delivered)


                        <tr>
                            <td>{{$cp->name}}</td>
                            <td>{{$outsourced->name}}</td>
                           {{--  @if($outsourced->companies()->count() > 0)
                            <td>{{$outsourced->companies()->first()->name}}</td>
                            @else
                            <td>- Não Informado</td>
                            @endif --}}
                            <td>{{$document->name}}</td>
                            <td>{{$delivered->expiration}}</td>
                            @if (preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$1$2$3', $delivered->expiration) < date('Ymd'))
                            <td  class="text-red"> {{__('general.Expired')}}</td>
                            @else
                            <td class="text-green"> {{__('general.Valid')}}</td>
                            @endif

                            <td>

                                @if($delivered->status == 0 or $delivered->status == '')
                                    <span class="text-green">
                                        {{ __('general.Normal') }}
                                    </span>
                                @elseif($delivered->status == 1)
                                    <span class="text-yellow">
                                        {{ __('general.Waiting correction') }}
                                    </span>
                                @elseif($delivered->status == 2)
                                    <span class="text-red">
                                        {{ __('general.Waiting for approval') }}
                                    </span>

                                @endif
                            </td>
                        </tr>

                        @endforeach
                        @endif

                        @endforeach
                        @endforeach
                        @endforeach
                        {{--
                        @endforeach  --}}



                    </tbody></table>
                    @endisset
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        @section('js')
        <script type="text/javascript">

            $( document ).ready(function() {

                $('.select2-responsive').change(function(){
                    $( "#report" ).submit();
                });
                $(".select2-responsive").select2({
                    width: 'resolve' // need to override the changed default
                });


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
