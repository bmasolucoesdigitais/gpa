@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')
@if(Request::is('g3/branches*'))
	<h1>{{__('general.Branches')}} - {{$branch->name}} - {{__('general.Documents')}} - {{$employee->name}}</h1>
@else
	<h1>{{__('general.Documents')}} - {{$employee->name}}</h1>
@endif

@stop

@section('content')

@canany(['fornecedor', 'master', 'G3 Employees Edit', 'G3 Edit', 'G3 Fornec'])
@if (Request::is('g3/branches*'))
<a href="{{route('branches.outsourceds.documents.attach', [$employee->id, $branch->id])}}" class="btn btn-app pull-right datatable">
@elseif (Request::is('g3/clients*'))
<a href="{{route('clients.employees.documents.attach', [$client, $employee->id])}}" class="btn btn-app pull-right datatable">
@else
<a href="{{route('employees.documents.attach', $employee->id)}}" class="btn btn-app pull-right datatable">
@endif
	<i class="fa fa-plus-square"></i> {{__('general.Add')}}
</a>
@endcanany

@if (Request::is('g3/branches*'))
	<a href="{{route('branches.outsourceds', $branch)}}" class="btn btn-app pull-right">
	<i class="fa fa-arrow-left"></i> {{__('general.Outsourceds')}}
@else
	<a href="{{route('employees')}}" class="btn btn-app pull-right">
	@can('master')
		<i class="fa fa-arrow-left"></i> {{__('general.Employees')}}
        @else
            @can('fornecedor')
            <i class="fa fa-arrow-left"></i> {{__('general.Employees')}}
            @else
                <i class="fa fa-arrow-left"></i> {{__('general.Outsourceds')}}
            @endcan
	@endcan
@endif
</a>

<div class="col-xs-12">
	<div class="box">

		<div class="box-body table-responsive">
			<table id="datatable" class="table table-hover">
				<thead><tr>


					<th>{{__('general.Name')}}</th>
						<th>{{__('general.Description')}}</th>
						<th>{{__('general.Delivereds')}}</th>



						@canany(['master', 'G3 Employees Edit', 'G3 Edit', 'fornecedor'] )
						<th>{{__('general.Actions')}}</th>
						@endcanany
					</tr>
				</thead>
				<tbody>
					@foreach ($documents as $document)

					<tr>
						<td>{{ $document->name}}</td>
						<td>{{ $document->description}}</td>
						<td>
							@canany(['master', 'G3 Employees Edit', 'G3 Edit', 'fornecedor'] )
							<div class="col-lg-1">

								@if (Request::is('g3/branches*'))
									<a class="" href="{{route('branches.outsourceds.employees.documents.delivereds.add', [null, $employee->id, $document->id, $branch->id ])}}">
                                @elseif(Request::is('g3/clients*'))
                                    <a class="" href="{{route('clients.employees.documents.delivereds.add', [$client, $employee->id, $document->id ])}}">
                                @else
									<a class="" href="{{route('employees.documents.delivereds.add', [$employee->id, $document->id ])}}">
								@endif
									<i class="fa fa-plus-square text-green " style="font-size: 32px"></i>
								</a>
							</div>
							@endcanany
							<div class="col-lg-11">

								

								@if ($document->delivereds()->where('employee_id', $employee->id)->where('fl_deleted', 0)->first())


								<table border="1" style="width: 98%;">

									<th class="text-center" style="padding: 2px 5px;">{{ __('general.Deliver date') }}</th>
									<th class="text-center" style="padding: 2px 5px;">{{ __('general.Expiration') }}</th>
									@cannot('gerente')
								@cannot('geral')
									<th class="text-center" style="padding: 2px 5px;">{{__( 'general.Files') }}</th>
								@endcan
								@endcan
									<th class="text-center" style="padding: 2px 5px;">{{__( 'general.Analyze') }}</th>
									@canany(['master', 'G3 Employees Edit', 'G3 Edit', 'fornecedor'] )
										<th class="text-center" style="padding: 2px 5px;">{{ __('general.Edit') }}</th>
									@endcanany
									<tr>
                                        {{--d  --d($employee->id) --}}
                                        @php
                                            if(isset($client)){
                                                $delivereds = $document->delivereds()->where('employee_id', $employee->id)->whereIn('company_id', [1,$client])->where('fl_deleted', 0)->orderBy('id', 'desc')->get()  ;
                                            }else{
                                                if(Auth::User()->can('fornecedor')){
                                                    $delivereds = $document->delivereds()->where('employee_id', $employee->id)->where('fl_deleted', 0)->orderBy('id', 'desc')->get()  ;
                                                }else{
                                                    $delivereds = $document->delivereds()->where('employee_id', $employee->id)->whereIn('company_id', [1, Auth::User()->company_id])->where('fl_deleted', 0)->orderBy('id', 'desc')->get()  ;
                                                }
                                            }

                                        @endphp
										@foreach ($delivereds as $delivered)
                                        <td class="text-center" style="padding: 2px 5px;">
											{{ $delivered->description }}  <br/>
										</td>

										<td style="padding: 2px 5px;">
											{{ preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$3/$2/$1', $delivered->expiration) }}
										</td>
										@cannot('gerente')
										@cannot('geral')
                                        <td style="">
                                        @php
                                            $fst = 0 ;
                                        @endphp
                                        @foreach ($delivered->files->where('fl_deleted', 0) as $file)
                                            @if ($fst != 0 )
                                                <br/>
                                            @endif
                                            <a href="/storage/uploads/{{ $file->file }}" target="_blank">{{ $file->name }}</a>
                                            @canany(['master', 'G3 Employees Edit', 'G3 Edit'] ) -
                                                <a id="btnDeleteFile"
                                                modalTitle="{{ __('general.Delete confirmation') }}"
                                                modalBody=" {{ __("general.Are you sure that you want to delete this ").__("general.file") }} ?"
                                                fid="{{ $file->id }}"
                                                eid="{{ $employee->id }}"
                                                class="btnDeleteFile" href=""
                                                data-toggle="modal"
                                                data-target="#confirmationModal
                                                "><i class="fa fa-trash text-red" style="font-size: 16px"></i></a>
                                            @endcanany
                                            @can('fornecedor')
                                            @if($delivered->status == 2)
                                                <a id="btnDeleteFile"
                                                modalTitle="{{ __('general.Delete confirmation') }}"
                                                modalBody=" {{ __("general.Are you sure that you want to delete this ").__("general.file") }} ?"
                                                fid="{{ $file->id }}"
                                                eid="{{ $employee->id }}"
                                                class="btnDeleteFile" href=""
                                                data-toggle="modal"
                                                data-target="#confirmationModal
                                                "><i class="fa fa-trash text-red" style="font-size: 16px"></i></a>
                                                @endif
                                            @endcan
                                            @php
                                                $fst = 1;
                                            @endphp
                                        @endforeach
                                        </td>
										@endcan
										@endcan
                                        <td>
                                            @if($delivered->status == 0)
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
                                        @canany(['master', 'G3 Employees Edit', 'G3 Edit'] )
                                            <td class="text-center" style="padding: 2px 5px;">
                                            @if (Request::is('g3/branches*'))
                                                <a class="" href="{{route('branches.outsourceds.documents.fileupload', [$employee->id, $delivered->id, $branch->id])}}" alt="upload"><i class="fa fa-upload text-aqua" style="font-size: 16px"></i></a> &nbsp&nbsp
                                                <a class="" href="{{route('branches.outsourceds.documents.delivereds.edit', [null, $employee->id, $delivered->id, $branch->id])}}" alt="upload"><i class="fa fa-pencil text-aqua" style="font-size: 16px"></i></a>
                                            @elseif (Request::is('g3/clients*'))
                                                <a class="" href="{{route('clients.employees.documents.fileupload', [$client, $employee->id, $delivered->id])}}" alt="upload"><i class="fa fa-upload text-aqua" style="font-size: 16px"></i></a> &nbsp&nbsp
                                                <a class="" href="{{route('clients.employees.documents.delivereds.edit', [$client, $employee->id, $delivered->id])}}" alt="upload"><i class="fa fa-pencil text-aqua" style="font-size: 16px"></i></a>

                                            @else
                                                <a class="" href="{{route('employees.documents.fileuploademp', [$employee->id, $delivered->id])}}" alt="upload"><i class="fa fa-upload text-aqua" style="font-size: 16px"></i></a> &nbsp&nbsp
                                                <a class="" href="{{route('employees.documents.delivereds.edit', [$employee->id, $delivered->id])}}" alt="upload"><i class="fa fa-pencil text-aqua" style="font-size: 16px"></i></a>
                                            @endif


                                            </td>
                                        @endcanany
                                        @can('fornecedor')
                                        <td class="text-center" style="padding: 2px 5px;">
                                            @if($delivered->status == 2)
                                            @if (Request::is('g3/branches*'))
                                                <a class="" href="{{route('branches.outsourceds.documents.fileupload', [$employee->id, $delivered->id, $branch->id])}}" alt="upload"><i class="fa fa-upload text-aqua" style="font-size: 16px"></i></a> &nbsp&nbsp
                                                <a class="" href="{{route('branches.outsourceds.documents.delivereds.edit', [null, $employee->id, $delivered->id, $branch->id])}}" alt="upload"><i class="fa fa-pencil text-aqua" style="font-size: 16px"></i></a>
                                            @elseif (Request::is('g3/clients*'))
                                                <a class="" href="{{route('clients.employees.documents.fileupload', [$client, $employee->id, $delivered->id])}}" alt="upload"><i class="fa fa-upload text-aqua" style="font-size: 16px"></i></a> &nbsp&nbsp
                                                <a class="" href="{{route('clients.employees.documents.delivereds.edit', [$client, $employee->id, $delivered->id])}}" alt="upload"><i class="fa fa-pencil text-aqua" style="font-size: 16px"></i></a>

                                            @else
                                                <a class="" href="{{route('employees.documents.fileuploademp', [$employee->id, $delivered->id])}}" alt="upload"><i class="fa fa-upload text-aqua" style="font-size: 16px"></i></a> &nbsp&nbsp
                                                <a class="" href="{{route('employees.documents.delivereds.edit', [$employee->id, $delivered->id])}}" alt="upload"><i class="fa fa-pencil text-aqua" style="font-size: 16px"></i></a>
                                            @endif

                                            @endif
                                            </td>
                                        @endcan
										{{-- @canany(['fornecedor'])
										<td class="text-center" style="padding: 2px 5px;">
										@if (Request::is('g3/branches*'))
											<a class="" href="{{route('branches.outsourceds.documents.fileupload', [$employee->id, $delivered->id, $branch->id])}}" alt="upload"><i class="fa fa-upload text-aqua" style="font-size: 16px"></i></a> &nbsp&nbsp
										@elseif (Request::is('g3/clients*'))
											<a class="" href="{{route('clients.employees.documents.fileupload', [$client, $employee->id, $delivered->id])}}" alt="upload"><i class="fa fa-upload text-aqua" style="font-size: 16px"></i></a> &nbsp&nbsp
										@else
											<a class="" href="{{route('employees.documents.fileuploademp', [$employee->id, $delivered->id])}}" alt="upload"><i class="fa fa-upload text-aqua" style="font-size: 16px"></i></a> &nbsp&nbsp
										@endif


										</td>
									@endcanany --}}

									</tr>
									@endforeach
								</table>
								@endif
							</div>




						</td>
						@canany(['master', 'G3 Employees Edit', 'G3 Edit'] )
						<td>
							<a
							id='btnDeleteDocument'
							modalTitle="{{ __('general.Delete confirmation') }}"
							modalBody="{{  __("general.Are you sure that you want to delete this ") }} {{ __("general.document")  }}?"
							href=""
							data-toggle="modal"
							data-target="#confirmationModal"
							did="{{ $document->id }}"
							eid="{{ $employee->id }}"
							class="btnDeleteDocument"
							>
							    <i class="fa fa-trash-o text-red pull-right" style="font-size: 32px" ></i>
						    </a>
					    </td>
                        @endcanany
						
					
				</tr>


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
 			"initComplete": function(settings, json) {
    			$('div.dataTables_filter input').focus();
  			}
		});

		//Delete ajax with confirmation modal

		$('.btnDeleteDocument').click(function(){
			$('.modal-body').html($(this).attr("modalBody"));
			$('.modal-title').html($(this).attr("modalTitle"));
			id = $(this).attr("did");
			eid = $(this).attr("eid");
			action = "{{ route('employees.documents.detach') }}?_token={{ csrf_token() }}";
					//alert($(this).attr("fileId"));

				});

		$('.btnDeleteFile').click(function(){
			$('.modal-body').html($(this).attr("modalBody"));
			$('.modal-title').html($(this).attr("modalTitle"));
			id = $(this).attr("fid");
			eid = $(this).attr("eid");
			action = "{{ route('employees.documents.filedelete') }}?_token={{ csrf_token() }}";
					//alert($(this).attr("fileId"));

				});
		$('#deleteConfirm').click(function(){
					//$('.modal-body').append("Confirmar a exclus??o deste arquivo"+ $(this).attr("file"));
				//alert(fileId);
				$('#confirmationModal').modal('hide');
				var data= {id:id, eid: eid};
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
					    	alert('Arquivo n??o encontrado!')
					    }
					}
				});
			});

				//fim------//Delete ajax with confirmation modal
			});
		</script>


		@endsection
		@endsection
