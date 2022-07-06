@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')

<h1>{{ __('general.Scheduled services') }} - {{$company->name}}</h1>


@stop

@section('content')

<a href="{{route('companies')}}" class="btn btn-app pull-right">
    <i class="fa fa-arrow-left"></i> {{__('general.Companies', )}}
</a>
@can('master')
<a href="{{route('companies.servicesscheduled.insert', $company->id)}}" class="btn btn-app pull-right">
	<i class="fa fa-plus-square"></i> {{__('general.Add')}}
</a>

@endcan

<div class="col-xs-12">
    <div class="box">


        <div class="box-body table-responsive">
            <table id="datatable" class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>{{__('general.Service')}}</th>
                        <th>{{__('general.Employee')}}</th>
                        <th>{{__('general.Store')}}</th>
                        <th>{{__('general.Date')}}</th>
                        <th>{{__('general.Abaco aproved')}}</th>
                        <th>{{__('general.Aproved')}}</th>
                        <th>{{__('general.Aproved by')}}</th>
                        <th>{{__('general.Edit')}}</th>
                        <th>{{__('general.Delete')}}</th>
                    </tr>
                </thead>
                <tbody>


                    @foreach ($company->services()->where('fl_deleted', 0)->get() as $service)
                    <tr>



                        <td>{{ $service->service}}</td>
                        <td>{{ $service->employee->name}}</td>
                        <td>{{ $service->client->name}}</td>
                        <td><span style="display:none">{{ preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$1$2$3', $service->date) }}</span>{{ preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$3/$2/$1', $service->date) }}</td>
                        <td>
                            @if ($service->aproved)
                                Sim
                            @else
                                Não
                            @endif
                        </td>
                        <td>
                            @if ($service->clientaproved)
                                Sim
                            @else
                                Não
                            @endif
                        </td>
                        <td>
                            @if ($service->user)
                            {{ $service->user->name }}
                            @endif
                        </td>

                        @canany(['master', 'G3 Company Edit'])
                        <td>
                            <a class="	" href="{{route('companies.servicesscheduled.edit', [$company->id, $service->id] )}}">

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

                    </tr>
                    @endforeach



                        </tbody>
                    </table>
                </div>

                </div>
                @section('js')
                <script type="text/javascript">

                    $( document ).ready(function() {
                        $('#datatable').DataTable( {
                            "order": [[ 3, "asc" ]],
                            "initComplete": function(settings, json) {
                                $('div.dataTables_filter input').focus();
                            }
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
