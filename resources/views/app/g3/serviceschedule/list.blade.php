@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')
<style>
    .modal-details{
        background: rgba(0, 0, 0, 0.87);
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0px;
        left:0px;
        display: none;
        z-index: 25000;
        padding: 25px;
    }
    .modal-aproval{
        background: rgba(0, 0, 0, 0.87);
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0px;
        left:0px;
        display: none;
        z-index: 25000;
        padding: 25px;
    }
    #modal-aproval-content{
        background: rgba(255, 255, 255, 1);
        
        max-width: 600px;
        margin: 30px auto;
        
        z-index: 25000;
        padding: 25px;
        border: 1px solid lightgrey;
    }

    .modal-close{
        float: right;
    }
    .modal-aproval-close{
        float: right;
    }

    .employees-details{
        margin: 30px auto;
        background: white;
    }

    .employees-details td{
        border: 1px black solid;

    }
    .employees-details th{
        border: 1px black solid;
        word-wrap: unset;
        transform-origin: 0 0;
        /* transform: rotate(270deg); */  
        /* white-space: nowrap;  */
    }
    .td-blank{
        background-color: white;
        color: white;
        
    }
    .td-approved{
        background-color: green;
        color: green;

    }
    .td-reproved{
        background-color: red;
        color: red;

    }
    .title-vertical{
        overflow: visible;

    }
    .docs th{
        max-width: 30px;
    } 

    .detail-hide{
        display: none;
    }
</style>
<h1>{{ __('general.Scheduled services') }}</h1>


@stop

@section('content')
@canany(['master', 'fornecedor'])
    
<a href="{{route('companies.servicesscheduled.insert')}}" class="btn btn-app pull-right">
    <i class="fa fa-plus"></i> {{__('general.Add')}}
</a>
@endcanany
<div class="col-md-12">
    <p>
        Legendas "Aprovado SESMT" 
        
        [<i class="fa fa-times text-red"></i> Aguardando aprovação]
         - [<i class="fa fa-exclamation text-yellow"></i> Aguardando correção]
         - [<i class="fa fa-check text-yellow"></i> Aprovação parcial]
         - [<i class="fa fa-check text-green"></i> Aprovação total]
    </p> 
</div>
<div class="col-xs-12">
    <div class="box">


        <div class="box-body table-responsive">
            <table id="datatable" class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>{{__('general.Service')}}</th>
                        <th>{{__('general.Company')}}</th>
                        @canany(['master', 'G3 Admin', 'cd', 'tecnico', 'fornecedor'])
                        <th>Ficha</th>
                        <th>APR</th>
                        <th>APR Assinada</th>
                        @endcanany
                        <th>{{__('general.Employees')}}</th>
                        <th>{{__('general.Store')}}</th>
                        <th>{{__('general.Initial date')}}</th>
                        <th>{{__('general.Final date')}}</th>
                       
                        <th>Abaco {{__('general.Status')}}</th>
                       
                        <th>{{__('general.Aproved')}} SESMT</th>
                        <th>{{__('general.Aproved by')}}</th>
                        <th>Ciência Responsável</th>
                        @canany(['master', 'G3 Company Edit', 'fornecedor'])
                            <th>{{__('general.Edit')}}</th>
                            <th>{{__('general.Delete')}}</th>
                        @endcanany
                    </tr>
                </thead>    
                <tbody>


                    @foreach ($scheduledservices as $service)
                    <tr>



                        <td>{{ $service->service}}</td>
                        <td>{{ $service->company->name}}</td>
                    @canany(['master', 'G3 Admin', 'cd', 'tecnico', 'fornecedor'])
                        <td>@if($service->file) 
                            <a href="/storage/uploads/{{ $service->file->file}}" target="_blank">
                                <i class="fa fa-fw fa-file-text-o " style="font-size: 32px"></i>
                            </a> 
                            @endif
                        </td>
                        
                        <td> 
                            @if($service->apr()->where('fl_deleted', 0)->count() > 0)
                                @if($service->apr()->where('fl_deleted',0)->first()->fl_status == 3)
                                    @canany(['master', 'G3 Admin'])
                                        <a href="{{route('companies.servicesscheduled.aprcreate', $service->id)}}">
                                            <i class="fa fa-fw fa-file-text-o text-green" style="font-size: 32px"></i>
                                        </a> 
                                        @endcanany
                                        @can('fornecedor')
                                        <a href="{{route('companies.servicesscheduled.aprcreate', $service->id)}}">
                                            <i class="fa fa-fw fa-file-text-o text-green" style="font-size: 32px"></i>
                                        </a> 
                                        @endcan
                                @else
                                    <a href="{{route('companies.servicesscheduled.aprcreate', $service->id)}}">
                                        <i class="fa fa-fw fa-file-text-o text-yellow" style="font-size: 32px"></i>
                                    </a>
                                @endif 
                            @endif
                            @if($service->apr()->count() <= 0)
                                @canany(['master', 'G3 Admin'])
                                        <i class="fa fa-fw fa-file-text-o text-red" style="font-size: 32px"></i>
                                @endcan
                                @can('fornecedor')
                                    <a href="{{route('companies.servicesscheduled.aprcreate', $service->id)}}">
                                        <i class="fa fa-fw fa-file-text-o text-red" style="font-size: 32px"></i>
                                    </a> 
                                @endcan
                                    
                            @endif
                            
                        </td>
                       
                        <td>
                            {{-- @if($service->apr()->where('fl_deleted', 0)->count() > 0) --}}
                                {{-- @if($service->apr()->where('fl_deleted',0)->first()->fl_status == 3) --}}
                                    @if($service->aprsigned_id == 0)
                                        <a href="{{route('companies.servicesscheduled.aprupload', $service->id)}}">
                                            <i class="fa fa-upload text-red" style="font-size: 32px"  data-toggle="tooltip" data-original-title="Envia / Substituir"></i>
                                        </a> 
                                    @else
                                    <a href="{{route('companies.servicesscheduled.aprupload', $service->id)}}">
                                        <i class="fa fa-upload text-red" style="font-size: 32px"  data-toggle="tooltip" data-original-title="Envia / Substituir"></i>
                                    </a> 
                                    &nbsp;
                                        <a href="/storage/uploads/{{$service->aprfile->file}}">
                                            <i class="fa fa-download text-green" style="font-size: 32px"  data-toggle="tooltip" data-original-title="Baixar"></i>
                                        </a> 
                                    @endif

                                {{-- @endif --}}
                            {{-- @else --}}
                            {{-- <i class="fa fa-times text-red" style="font-size: 32px"></i> --}}
                            {{-- @endif --}}

                        </td>
                    @endcanany
                        <td>
                            {{-- <button class="button-detail" ><i class="fa fa-eye"></i></button> --}}
                            <a href="" class="button-detail" data-service="{{$service->id}}">
                                <i class="fa fa-eye"  style="font-size: 32px"></i>
                            </a>
                            <div class="detail-hide" id="detail_{{$service->id}}">
                            
                            <table class="employees-details" >
                                
                                @php
                                    $services_employees = array();
                                    $documents_employees = array();
                                    foreach($service->employees as $employee){
                                        foreach($employee->services()->where('fl_deleted', 0)->get() as $item){
                                            //dd($item);
                                            $services_employees[$item->id] = $item->initials;
                                       }
                                        foreach($employee->documents()->where('fl_deleted', 0)->get() as $item){
                                            $documents_employees[$item->id] =$item->name;
                                       }
                                    }
                                    /* var_dump($documents_employees);
                                    var_dump($services_employees); */
                                @endphp
                                    <tr>
                                        <th>Nome</th>
                                        <th>CPF</th>
                                        @foreach ($documents_employees as $key=>$value)
                                            <th class="docs">
                                                {{$value}}</th>
                                        @endforeach                                        
                                        @foreach ($services_employees as $key=>$value)
                                            <th class="docs">
                                                {{$value}}</th>
                                        @endforeach                                        
                                    </tr>
                                @foreach ($service->employees as $employee)
                                <tr>
                                    <td>
                                        @canany(['master', 'G3 Admin', 'cd', 'tecnico'])
                                            <a href="{{route('employees.documents_services', $employee->id)}}" target="_blank">
                                        @endcanany
                                            {{$employee->name}}
                                        @canany(['master', 'G3 Admin', 'cd', 'tecnico'])
                                            </a>   
                                        @endcanany
                                        </td>
                                <td> {{$employee->cpf}}</td>
                                    @foreach ($documents_employees as $key=>$value)
                                        @php $item_control = false @endphp
                                        @foreach ($employee->documents as $item)
                                            @if($item->id == $key)
                                                @php $item_control = true @endphp    
                                                @if($item->delivereds()->where('fl_deleted', 0)->where('employee_id', $employee->id)->where('status', 0)->whereDate('expiration', '>=', date('Y-m-d'))->count() > 0)
                                                    <td class="td-approved">
                                                        V
                                                    </td>
                                                @else
                                                    <td class="td-reproved">
                                                        X   
                                                    </td>
                                                @endif
                                                
                                            @endif
                                            
                                        @endforeach
                                        @if(!$item_control)
                                                <td class="td-blank"></td>
                                                @endif
                                    @endforeach                                        
                                    @foreach ($services_employees as $key=>$value)
                                        @php $item_control = false @endphp
                                        @foreach ($employee->services()->where('fl_deleted', 0)->get() as $empserv)
                                         
                                            @if($empserv->id == $key)
                                            

                                                    
                                                @php $item_control = true @endphp
                                                @php $item_valid = false @endphp
                                                @foreach ($empserv->documents as $item)
                                                    @if($employee->delivereds()->where('employee_id', $employee->id)->where('document_id', $item->id)->where('fl_deleted', 0)->where('status', 0)->whereDate('expiration', '>=', date('Y-m-d'))->count() > 0)
                                                        @php $item_valid = true @endphp
                                                    @endif
                                                    
                                                @endforeach
                                            @endif
                                        
                                        @endforeach
                                        @if(!$item_control)
                                            <td class="td-blank"></td>
                                        @else  
                                            @if(!$item_valid)
                                                <td class="td-reproved">X</td>
                                            @else
                                                <td class="td-approved">V</td>
                                            @endif
                                        @endif  
                                    @endforeach     
                                        
                                  {{--  /*  <tr>
                                        <td>

                                            <a href="{{route('employees.documents_services', $employee->id)}}" target="_blank">
                                                {{$employee->name}}
                                            </a>
                                        </td>
                                    </tr>  */ --}}
                                </tr>
                                @endforeach
                            </table>
                        </div>
                        </td>
                        <td>{{$service->store->filial}} - {{ $service->store->name}}</td>
                        <td><span style="display:none">{{ preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$1$2$3', $service->date_ini) }}</span>{{ preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$3/$2/$1', $service->date_ini) }}</td>
                        <td><span style="display:none">{{ preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$1$2$3', $service->date_end) }}</span>{{ preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$3/$2/$1', $service->date_end) }}</td>
                       
                            
                        <td>

                            @if ($service->aproved ==0)
                             <span class="text-red">{{__('general.Waiting for documents')}}</span>
                            @endif
                            @if ($service->aproved ==1)
                             <span class="text-yellow">{{__('general.Waiting correction')}}</span>
                            @endif
                            @if ($service->aproved ==2)
                             <span class="text-yellow">Documento expirado</span>
                            @endif
                            @if ($service->aproved ==3)
                             <span class="text-green">Aprovado parcial</span>
                            @endif
                            @if ($service->aproved ==4)
                             <span class="text-green">{{__('general.Aproved')}}</span>
                            @endif
                            
                            
                        </td>
                        
                        
                        <td>
                            @can('G3 Admin')
                            @if ($service->aproved > 2)
                                <a href="" id="buttonAprove" class='change-aprovation' aproveid="{{$service->id}}">
                            @endif
                            @endcan
                            
                            @if ($service->clientaproved == 3)
                            <i class="fa  fa-check text-green "  style="font-size: 32px" data-toggle="tooltip" title="" data-original-title="Aprovado"></i>
                            @endif
                            @if ($service->clientaproved == 2)
                            <i class="fa  fa-check text-yellow "  style="font-size: 32px" data-toggle="tooltip" title="" data-original-title="Aprovado parcial"></i>
                            @endif
                            @if ($service->clientaproved == 1)
                            <i class="fa  fa-exclamation text-yellow "  style="font-size: 32px" data-toggle="tooltip" title="" data-original-title="Aguardando correção"></i>
                            @endif
                            @if ($service->clientaproved == 0)
                            <i class="fa  fa-times text-red " style="font-size: 32px" data-toggle="tooltip" title="" data-original-title="Aguardando aprovação"></i>
                            @endif
                            @can('G3 Admin')
                            @if ($service->aproved > 2)
                                </a>
                            @endif
                            @endcan
                        </td>
                        
                        <td>
                            @if ($service->user)
                            {{ $service->user->name }}
                            @endif
                        </td>

                        <td>
                            @can('gerente')
                            @if ($service->aproved > 2 && $service->clientaproved > 1 && $service->techaproved == 0)
                                <a href="" id="buttonAprove" class='change-techaprovation' aproveid="{{$service->id}}">
                            @endif
                            @endcan
                            
                            @if ($service->techaproved == 1)
                            <i class="fa  fa-check text-green " style="font-size: 32px"></i>
                            @else
                            <i class="fa  fa-times text-red "  style="font-size: 32px"></i>
                            @endif
                            @can('gerente')
                            @if ($service->aproved > 2 && $service->clientaproved > 1 && $service->techaproved == 0)
                                </a>
                            @endif
                            @endcan
                        </td>

                       @canany(['master', 'G3 Company Edit'])
                        <td>
                            <a class="	" href="{{route('companies.servicesscheduled.edit', $service->id)}}">

                                <i class="fa fa-pencil text-green" style="font-size: 32px"></i>

                            </a>
                        </td>

                        <td>
                            <a
                            id='btnDeleteService'
							modalTitle="{{ __('general.Delete confirmation') }}"
							modalBody="{{  __("general.Are you sure that you want to delete this ") }} {{ __("general.Scheduled services")  }}?"
							href=""
							data-toggle="modal"
							data-target="#confirmationModal"
                            sid = "{{$service->id}}"
                            class="btnDeleteService">

                                <i class="fa fa-trash-o text-red " style="font-size: 32px"></i>
                            </a>
                        @endcanany
                       @canany(['fornecedor'])
                       @if ($service->clientaproved != 2)
                        <td>
                            <a class="	" href="{{route('companies.servicesscheduled.edit', $service->id)}}">

                                <i class="fa fa-pencil text-green" style="font-size: 32px"></i>

                            </a>
                        </td>

                        <td>
                            <a
                            id='btnDeleteService'
							modalTitle="{{ __('general.Delete confirmation') }}"
							modalBody="{{  __("general.Are you sure that you want to delete this ") }} {{ __("general.Scheduled services")  }}?"
							href=""
							data-toggle="modal"
							data-target="#confirmationModal"
                            sid = "{{$service->id}}"
                            class="btnDeleteService">

                                <i class="fa fa-trash-o text-red " style="font-size: 32px"></i>
                            </a>
                            @else
                            <td></td>
                            <td></td>
                            @endif
                        @endcanany

                    </tr>
                    @endforeach



                        </tbody>
                    </table>
                </div>

                <div class="modal-details" id="modal-details1">
                    {{-- <div style="width: 100%; padding:10px">
                        <button class="btn btn-default modal-close"><i class="fa fa-times " style="font-size: 32px"></i></button>
                        </div> --}}
                        <div class="box box-default">
                            <div class="box-header with-border">
                            <i class="fa fa-eye"></i>
                            <h3 class="box-title">{{__('general.Documents')}}</h3>
                            <button type="button" class="close modal-close btn btn-default"  aria-label="Close">
                                <i class="fa fa-times"></i>
                            </button>
                            </div>
                            
                            <div class="box-body">
                                <table>
                                    <tr>
                                        <td style="margin:5px solid white; padding: 5px">Legendas:</td>
                                        <td style="border:1px solid lightgrey; margin:5px solid white; padding: 5px; background-color: red; color:white;">Não aprovado</td>
                                        <td style="border:1px solid lightgrey; margin:5px solid white; padding: 5px; background-color: green; color:white">Aprovado</td>
                                        <td style="border:1px solid lightgrey; margin:5px solid white; padding: 5px; background-color: white; color:black">Não apto</td>
                                    </tr>
                                </table>
                                <div id="modal-content"></div>
                            </div>
                            
                        </div>
                    
                </div>

                <div class="modal-aproval" id="modal-aproval">
                    {{-- <div style="width: 100%; padding:10px; ">
                        <button class="btn btn-default modal-aproval-close"><i class="fa fa-times " style="font-size: 32px"></i></button>
                        </div> --}}

                        <div class="box box-default">
                            <div class="box-header with-border">
                            <i class="fa fa-check"></i>
                            <h3 class="box-title">{{__('general.Status')}}</h3>
                            <button type="button" class="close modal-aproval-close btn btn-default"  aria-label="Close">
                                <i class="fa fa-times"></i>
                            </button>
                            </div>
                            
                            <div class="box-body">
                                <div id="modal-aproval-content">
                                    <form action="" id='formClientAproval'>
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select class="form-control" name="modal-aproval-status" id="modal-aproval-status">
                                            <option value="0">Aguardando aprovação</option>
                                            <option value="1">Aguardando Correção</option>
                                            <option value="2">Aprovado parcial</option>
                                            <option value="3">Aprovado</option>
                                            </select>
                                            
                                        </div>
                                        <div class="form-group">
                                            <label>Observação</label>
                                            <textarea class="form-control" name="modal-aproval-obs" id="modal-aproval-obs" cols="30" rows="5"></textarea>
                                            </select>
                                            
                                        </div>
                                        <button class="btn btn-success send-aprovation">Enviar</button>
            
                                    </form>
                                </div>
                            </div>
                            
                        </div>
                   
                </div>

                </div>
                @section('js')
                <script type="text/javascript">

                    $( document ).ready(function() {
                        $('[data-toggle="tooltip"]').tooltip({
                            placement : 'top'
                        });

                        $('.button-detail').click(function(event){
                            event.preventDefault();
                            console.log($(this).data('service'));
                            source = $('#detail_'+$(this).data('service')).html();
                            $('#modal-content').html(source);
                            $('#modal-details1').show();
                            
                            
                        })

                        $('.change-aprovation').click(function(){
                            event.preventDefault();
                           /*  console.log($(this).data('service'));
                            source = $('#detail_'+$(this).data('service')).html(); */
                           // $('#modal-aproval').html(source);
                           modalAproveId = $(this).attr('aproveid');
                            $('#modal-aproval').show();
                            
                            
                        })
                        
                        
                        $('.modal-close').click(function(event){
                            event.preventDefault();
                            $('#modal-details1').hide();
                            
                        })

                        $('.modal-aproval-close').click(function(event){
                            event.preventDefault();
                            $('#modal-aproval').hide();
                            
                        })



                        $('#datatable').DataTable( {

                          /*   searchPanes:{
                                cascadePanes: true,
                                layout: 'columns-3'
                            },
                            
                            dom: 'PBfrtipl', */


                            @canany(['master', 'G3 Admin', 'cd', 'tecnico'])
                                "order": [[ 7, "asc" ],[ 8, "asc" ]],

                               
                            @endcanany

                            @canany(['geral', 'gerente',])
                                "order": [[ 4, "asc" ],[ 5, "asc" ]],

                               
                            @endcanany

                           
                            
                            
                            "initComplete": function(settings, json) {
                                $('div.dataTables_filter input').focus();
                            }
                        });

                        $('.send-aprovation').click(function(e){
                            e.preventDefault();
                            target = $(this);
                            $(this).prop("disabled",true);
                    
                            status = $('#modal-aproval-status').val();
                            obs = $('#modal-aproval-obs').val();

                            data = {};
                            data['id'] = modalAproveId;
                            data['status'] = status;
                            data['obs'] = obs;
                            jQuery.ajax({
                            type: "POST",
                            data: data,
                            url: '/g3/companies/servicesscheduled/changeaprovation?_token={{ csrf_token() }}',
                            success: function(data) {
                                
                                document.location.reload(true);
                               /*  if (data == 1) {
                                    $(target).html( '<i class="fa  fa-check text-green " style="font-size: 32px"></i>');
                                    
                                }else{
                                    $(target).html( '<i class="fa  fa-times text-red " style="font-size: 32px"></i>');
                                } */
                            }
                        });
                        });
                        $('.change-techaprovation').click(function(e){
                            e.preventDefault();
                            target = $(this);
                            id = $(this).attr('aproveid');
                            
                            jQuery.ajax({
                            type: "GET",
                            url: '/g3/companies/servicesscheduled/changetechaprovation/'+id,
                            success: function(data) {
                                window.location.reload();

                                if (data == 1) {
                                    $(target).html( '<i class="fa  fa-check text-green " style="font-size: 32px"></i>');
                                    
                                }else{
                                    $(target).html( '<i class="fa  fa-times text-red " style="font-size: 32px"></i>');
                                }
                            }
                        });
                        });

                    });

                    $('.btnDeleteService').click(function(){
                        $('.modal-body').html($(this).attr("modalBody"));
                        $('.modal-title').html($(this).attr("modalTitle"));
                        sid = $(this).attr("sid");
                        action = "{{ route('companies.servicesscheduled.delete') }}?_token={{ csrf_token() }}";
                                //alert($(this).attr("fileId"));
				    });

                    $('#deleteConfirm').click(function(){
                            //$('.modal-body').append("Confirmar a exclusão deste arquivo"+ $(this).attr("file"));
                        //alert(fileId);
                        $('#confirmationModal').modal('hide');
                        var data= {id:sid};
                        console.log(data);
                        console.log(action);

                        jQuery.ajax({
                            type: "POST",
                            url: action,
                            data: data,
                            success: function(data) {

                                if (data == 1) {
                                    // console.log(data);
                                    location.reload();

                                }else{
                                    alert('Arquivo não encontrado!')
                                }
                            }
                        });
                    });

                </script>

                


                @endsection
                @endsection
