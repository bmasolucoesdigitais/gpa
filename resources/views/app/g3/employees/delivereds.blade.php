@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')
@if (Request::is('g3/branches*'))
	<h1>{{__('general.Branches')}} - {{ $branch->name }} - {{$data['employee']->name}} - {{__('general.Services')}} - {{__('general.Documents')}}</h1>
@else
<h1>{{__('general.Employees')}} - {{$data['employee']->name}} - {{__('general.Services')}} - {{__('general.Documents')}}</h1>
@endif

@stop

@section('content')

@php
	$employee = $data['employee'];
@endphp

@canany(['master', 'G3 Employees Edit', 'G3 Edit', 'fornecedor'] )
{{-- <a href="{{route('employees.services.add', $data['employee']->id)}}" class="btn btn-app pull-right">
	<i class="fa fa-plus-square"></i> {{__('general.Add')}}
</a> --}}
@endcanany
@if (Request::is('g3/branches*'))
	<a href="{{route('branches.outsourceds.services', [$employee->id, $branch->id])}}" class="btn btn-app pull-right">
@else
	<a href="{{route('employees.services', $employee->id)}}" class="btn btn-app pull-right">
@endif
	<i class="fa fa-arrow-left"></i> {{__('general.Services')}}
</a>

<div class="col-xs-12">
	<div class="box">

		<div class="box-body table-responsive">
			<table id="datatable" class="table table-hover">
				<thead><tr>


					<th>{{__('general.Name')}}</th>
						<th>{{__('general.Description')}}</th>
						<th>{{__('general.Delivereds')}}</th>




						@canany(['master', 'G3 Employees Edit', 'G3 Edit'] )
						<th>{{__('general.Actions')}}</th>
						@endcanany
					</tr>
				</thead>
				<tbody>
					@foreach ($data['service']->documents as $document)

					<tr>
						<td>{{ $document->name}}</td>
						<td>{{ $document->description}}</td>
						<td>

							<div class="col-lg-1">
@canany(['master', 'G3 Employees Edit', 'G3 Edit', 'fornecedor'] )
								@if (Request::is('g3/branches*'))
									<a class="" href="{{route('branches.outsourceds.services.delivereds.add', [$data['employee']->id, $document->id, $data['service']->id, $branch->id])}}">
								@else
									<a class="" href="{{route('employees.delivereds.add', [$data['employee']->id, $document->id, $data['service']->id])}}">
								@endif
									<i class="fa fa-plus-square text-green " style="font-size: 32px"></i>
								</a>
@endcanany
							</div>
							<div class="col-lg-11">

								@if ($employee->delivereds->where('document_id', $document->id)->where('fl_deleted', 0))


								<table border="1" style="width: 98%;">

									<th class="text-center" style="padding: 2px 5px;">{{ __('general.Deliver date') }}</th>
									<th class="text-center" style="padding: 2px 5px;">{{ __('general.Expiration') }}</th>
                                    <th class="text-center" style="padding: 2px 5px;">{{__( 'general.Files') }}</th>
                                    <th class="text-center" style="padding: 2px 5px;">{{__( 'general.Analyze') }}</th>
									@canany(['master', 'G3 Employees Edit', 'G3 Edit', 'fornecedor'] )
									<th class="text-center" style="padding: 2px 5px;">{{ __('general.Edit') }}</th>
                                    @endcanany
									<tr>

										@foreach ($employee->delivereds()->where('document_id', $document->id)->where('fl_deleted', 0)->orderBy('id', 'desc')->take(1)->get() as $delivered)
										<td class="text-center" style="padding: 2px 5px;">


											{{ $delivered->description }}  <br/>


										</td>






										<td style="padding: 2px 5px;">
											{{  date('d/m/Y', strtotime($delivered->expiration)) }}



										</td>

											<td style="">
											@php
												$fst = 0 ;
											@endphp
											@foreach ($delivered->files->where('fl_deleted', 0) as $file)

											@if ($fst != 0 )
												<br/>
											@endif
											<a href="/storage/uploads/{{ $file->file }}" target="_blank">{{ $file->name }}</a>
											@canany(['master', 'G3 Employees Edit', 'G3 Edit'] )
 -
											<a id="btnDeleteFile"
											modalTitle="{{ __('general.Delete confirmation') }}"
											modalBody=" {{ __("general.Are you sure that you want to delete this ").__("general.file") }} ?"
											docId="{{ $file->id }}"
											companyId="{{ $employee->id }}"
											class="btnDeleteFile " href=""
											data-toggle="modal"
											data-target="#confirmationModal
											"><i class="fa fa-trash text-red" style="font-size: 16px"></i></a>
@endif
											@php
												$fst = 1;
											@endphp



											@endforeach
                                        </td>
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
											<a class="" href="{{route('branches.outsourceds.services.delivereds.upload', [$data['employee']->id, $data['service']->id, $delivered->id, $branch->id ])}}" alt="upload"><i class="fa fa-upload text-aqua" style="font-size: 16px"></i></a> &nbsp&nbsp
											@else
											<a class="" href="{{route('employees.delivereds.upload', [$data['employee']->id, $data['service']->id, $delivered->id])}}" alt="upload"><i class="fa fa-upload text-aqua" style="font-size: 16px"></i></a> &nbsp&nbsp
											@endif

											@if (Request::is('g3/branches*'))
												<a class="" href="{{route('branches.outsourceds.services.delivereds.edit',[$data['employee']->id, $data['service']->id, $delivered->id, $branch->id])}}" alt="upload"><i class="fa fa-pencil text-aqua" style="font-size: 16px"></i></a>
											@else
												<a class="" href="{{route('employees.delivereds.edit',[$data['employee']->id, $data['service']->id, $delivered->id])}}" alt="upload"><i class="fa fa-pencil text-aqua" style="font-size: 16px"></i></a>
											@endif

										</td>
                                        @endcanany
										@can('fornecedor')
										<td class="text-center" style="padding: 2px 5px;">
                                        @if($delivered->status == 2)
											@if (Request::is('g3/branches*'))
											<a class="" href="{{route('branches.outsourceds.services.delivereds.upload', [$data['employee']->id, $data['service']->id, $delivered->id, $branch->id ])}}" alt="upload"><i class="fa fa-upload text-aqua" style="font-size: 16px"></i></a> &nbsp&nbsp
											@else
											<a class="" href="{{route('employees.delivereds.upload', [$data['employee']->id, $data['service']->id, $delivered->id])}}" alt="upload"><i class="fa fa-upload text-aqua" style="font-size: 16px"></i></a> &nbsp&nbsp
											@endif

											@if (Request::is('g3/branches*'))
												<a class="" href="{{route('branches.outsourceds.services.delivereds.edit',[$data['employee']->id, $data['service']->id, $delivered->id, $branch->id])}}" alt="upload"><i class="fa fa-pencil text-aqua" style="font-size: 16px"></i></a>
											@else
												<a class="" href="{{route('employees.delivereds.edit',[$data['employee']->id, $data['service']->id, $delivered->id])}}" alt="upload"><i class="fa fa-pencil text-aqua" style="font-size: 16px"></i></a>
											@endif
                                        @endif
										</td>
                                        @endcan

									</tr>
									@endforeach
								</table>
								@endif
							</div>




						</td>

						@canany(['master', 'G3 Employees Edit', 'G3 Edit', 'fornecedor'] )
                        <td>
							<!--<a
							id='btnDeleteDocument'
							modalTitle="{{ __('general.Delete confirmation') }}"
							modalBody="{{  __("general.Are you sure that you want to delete this ") }} {{ __("general.document")  }}?"
							href=""
							data-toggle="modal"
							data-target="#confirmationModal"
							docId="{{ $document->id }}"
							companyId="{{ $employee->id }}"
							class="btnDeleteDocument"
							>
							<i class="fa fa-trash-o text-red pull-right" style="font-size: 32px" ></i>-->
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

		/*$('.btnDeleteDocument').click(function(){
			$('.modal-body').html($(this).attr("modalBody"));
			$('.modal-title').html($(this).attr("modalTitle"));
			docId = $(this).attr("docId");
			companyId = $(this).attr("companyId");
			action = "{{-- route('employees.detach') }}?_token={{ csrf_token() --}}";
					//alert($(this).attr("fileId"));

				});*/

		$('.btnDeleteFile').click(function(){
			$('.modal-body').html($(this).attr("modalBody"));
			$('.modal-title').html($(this).attr("modalTitle"));
			docId = $(this).attr("docId");
			companyId = $(this).attr("companyId");
			action = "{{ route('employees.outsourced.filedelete') }}?_token={{ csrf_token() }}";
					//alert($(this).attr("fileId"));

				});
		$('#deleteConfirm').click(function(){
					//$('.modal-body').append("Confirmar a exclusão deste arquivo"+ $(this).attr("file"));
				//alert(fileId);
				$('#confirmationModal').modal('hide');
				var data= {id:docId, cid: companyId};
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

				//fim------//Delete ajax with confirmation modal
			});
		</script>


		@endsection
		@endsection
