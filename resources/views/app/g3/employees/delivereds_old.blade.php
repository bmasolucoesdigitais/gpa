@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')
<h1>{{__('general.Employees')}} - {{$data['employee']->name}} - {{__('general.Services')}} - {{__('general.Documents')}}</h1>
<!-- metodo de entrada para translations {{ __('employees.test') }} -->
@stop

@section('content')
<a href="{{route('employees.services', $data['employee']->id)}}" class="btn btn-app pull-right">
	<i class="fa fa-arrow-left"></i> {{__('general.Services')}}
</a>

<div class="col-xs-12">
	<div class="box">
		
		<div class="box-body table-responsive">
			<table id="datatable" class="table table-hover">
				<thead>
					<tr>
						
						<th>{{__('general.Document')}}</th>
						<th>{{__('general.Last deliver')}}</th>
						<th>{{__('general.Last expiration')}}</th>
						<th>{{__('general.Files')}}</th>
						
						<th> {{__('general.Actions')}}</th>

					</tr>
				</thead>
				<tbody>
					@foreach ($data['service']->documents as $document)
					
					<tr>

						
						<td>{{$document->name}}</a></td>
						<td>
							


							@if(Auth::User()->can('master'))

							@php
							if($document->Delivereds()->where('employee_id', $data['employee']->id)){
								$delivered = 
								$document->Delivereds()
								->where('employee_id', $data['employee']->id)
								//->where('company_id', Auth::User()->company_id)
								->orderBy('expiration', 'desc')->first();
								//dd($delivered->files);
								if(isset($delivered->description)){
									echo(

										$delivered->description
										."</td>

										<td>".date('d/m/Y', strtotime($delivered->expiration))."</td>

										<td>"

									);

									
									$files = $delivered->files->where('fl_deleted', 0);
									foreach ($files as $file) {
										echo('<a href="/storage/uploads/'.$file->file.'" target="_blank"> '.$file->name."</a> "); 
										echo ('
											<a id="btnDeleteFile" 
											modalTitle="'.__('general.Delete confirmation').'"
											modalBody="'. __("general.Are you sure that you want to delete this ").__("general.file").'?"
											fileId="'.$file->id.'" 
											employeeId="'.$data['employee']->id.'" 
											class="btnDeleteFile pull-right" href="" 
											data-toggle="modal" 
											data-target="#confirmationModal
											"><i class="fa fa-trash text-red" style="font-size: 16px"></i></a><br/>
											');
									}
									echo("</td>");
									//dd($delivered->files);
								}else{
									echo("N/A</td><td></td><td></td>");
								}
							}else{

								
							}
							@endphp
								
							@else

							@php
							if($document->Delivereds()->where('employee_id', $data['employee']->id)){
								$delivered = 
								$document->Delivereds()
								->where('employee_id', $data['employee']->id)
								->whereIn('company_id', [Auth::User()->company_id,1])
								->orderBy('expiration', 'desc')->first();
								//dd($delivered->files);
								if(isset($delivered->description)){
									echo(

										$delivered->description
										."</td>

										<td>".date('d/m/Y', strtotime($delivered->expiration))."</td>

										<td>"

									);
										$files = $delivered->files->where('fl_deleted', 0);
									foreach ($files as $file) {
										echo('<a href="/storage/uploads/'.$file->file.'" target="_blank"> '.$file->name."</a> "); 
										echo ('
											<a id="btnDeleteFile" 
											modalTitle="'.__('general.Delete confirmation').'"
											modalBody="'. __("general.Are you sure that you want to delete this ").__("general.file").'?"
											fileId="'.$file->id.'" 
											employeeId="'.$data['employee']->id.'" 
											class="btnDeleteFile pull-right" href="" 
											data-toggle="modal" 
											data-target="#confirmationModal
											"><i class="fa fa-trash text-red" style="font-size: 16px"></i></a><br/>
											');
									}
									echo("</td>");
									//dd($delivered->files);
								}else{
									echo("N/A</td><td></td><td></td>");
								}
							}else{

								
							}
							@endphp

							@endif
							<td class="pull-right">@if(isset($delivered)) <a class="" href="{{route('employees.delivereds.upload', [$data['employee']->id, $data['service']->id, $delivered->id])}}" alt="upload"><i class="fa fa-upload text-aqua" style="font-size: 32px"></i></a>@endif
								<a class="" href="{{route('employees.delivereds.add', [$data['employee']->id, $document->id, $data['service']->id])}}"><i class="fa fa-plus-square text-green" style="font-size: 32px"></i></a>
							</td>


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
				$('.btnDeleteFile').each(function(index){
					$(this).click(function(){
									$('.modal-body').html($(this).attr("modalBody"));
									$('.modal-title').html($(this).attr("modalTitle"));
									fileId = $(this).attr("fileId");
									employeeId = $(this).attr("employeeId");
									//alert($(this).attr("fileId"));

								});
				});
				
				$('#deleteConfirm').click(function(){
					//$('.modal-body').append("Confirmar a exclusão deste arquivo"+ $(this).attr("file"));
				//alert(fileId);
				$('#confirmationModal').modal('hide');
				var data= {id:fileId, eid: employeeId};
				jQuery.ajax({
				    type: "POST",
				    url: '{{ route('employees.outsourced.filedelete') }}'+'?_token=' + '{{ csrf_token() }}',
				    data: data,
				    success: function(data) {

				         if (data = 1) {
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
