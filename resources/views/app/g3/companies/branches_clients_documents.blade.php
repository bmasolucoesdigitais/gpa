@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')
<h1>{{__('general.Documents')}} - {{$company->name}}</h1>

@stop

@section('content')



<a href="{{route('companies.branches.client.documents.attach', [$branch, $company->id])}}" class="btn btn-app pull-right datatable">
	<i class="fa fa-plus-square"></i> {{__('general.Add')}}
</a>
<a href="{{route('companies.branches.client', [$branch, $company->id])}}" class="btn btn-app pull-right">
	<i class="fa fa-arrow-left"></i> {{__('general.Companies')}}
</a>

<div class="col-xs-12">
	<div class="box">
		
		<div class="box-body table-responsive">
			<table id="datatable" class="table table-hover">
				<thead><tr>

					
					<th>{{__('general.Name')}}</th>
						<th>{{__('general.Description')}}</th>
						<th>{{__('general.Delivereds')}}</th>




						<th>{{__('general.Actions')}}</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($documents as $document)

					<tr>
						<td>{{ $document->name}}</td>
						<td>{{ $document->description}}</td>
						<td>

							<div class="col-lg-1">

								<a class="" href="{{route('companies.branches.client.documents.delivereds.add', [$company->id, $document->id, $branch ])}}">
									<i class="fa fa-plus-square text-green " style="font-size: 32px"></i>
								</a>
							</div>			
							<div class="col-lg-11">


								@if ($document->delivereds()->where('company_id', $company->id)->where('fl_deleted', 0)->first())
									
								
								<table border="1" style="width: 98%;">

									<th class="text-center" style="padding: 2px 5px;">{{ __('general.Deliver date') }}</th>
									<th class="text-center" style="padding: 2px 5px;">{{ __('general.Expiration') }}</th>
									<th class="text-center" style="padding: 2px 5px;">{{__( 'general.Files') }}</th>
									<th class="text-center" style="padding: 2px 5px;">{{ __('general.Edit') }}</th>
									<tr>

										@foreach ($document->delivereds()->where('company_id', $company->id)->where('fl_deleted', 0)->orderBy('id', 'desc')->take(2)->get() as $delivered)
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
											<a href="/storage/uploads/{{ $file->file }}" target="_blank">{{ $file->name }}</a> - 
											<a id="btnDeleteFile" 
											modalTitle="{{ __('general.Delete confirmation') }}"
											modalBody=" {{ __("general.Are you sure that you want to delete this ").__("general.file") }} ?"
											docId="{{ $file->id }}" 
											companyId="{{ $company->id }}"
											class="btnDeleteFile " href="" 
											data-toggle="modal" 
											data-target="#confirmationModal
											"><i class="fa fa-trash text-red" style="font-size: 16px"></i></a>
											@php
												$fst = 1;
											@endphp 
												


											@endforeach
										</td>	
										<td class="text-center" style="padding: 2px 5px;">
											<a class="" href="{{route('companies.documents.fileupload', [$company->id, $delivered->id])}}" alt="upload"><i class="fa fa-upload text-aqua" style="font-size: 16px"></i></a> &nbsp&nbsp
											<a class="" href="{{route('companies.documents.delivereds.edit', [$company->id, $delivered->id])}}" alt="upload"><i class="fa fa-pencil text-aqua" style="font-size: 16px"></i></a>

										</td>

									</tr>
									@endforeach
								</table>
								@endif
							</div>

							


						</td>

						<td>
							<a 
							id='btnDeleteDocument' 
							modalTitle="{{ __('general.Delete confirmation') }}"
							modalBody="{{  __("general.Are you sure that you want to delete this ") }} {{ __("general.document")  }}?"
							href="" 
							data-toggle="modal" 
							data-target="#confirmationModal"
							docId="{{ $document->id }}"
							companyId="{{ $company->id }}"
							class="btnDeleteDocument"
							>
							<i class="fa fa-trash-o text-red pull-right" style="font-size: 32px" ></i>
						</a>
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

		$('.btnDeleteDocument').click(function(){
			$('.modal-body').html($(this).attr("modalBody"));
			$('.modal-title').html($(this).attr("modalTitle"));
			docId = $(this).attr("docId");
			companyId = $(this).attr("companyId");
			action = "{{ route('companies.documents.detach') }}?_token={{ csrf_token() }}";
					//alert($(this).attr("fileId"));

				});

		$('.btnDeleteFile').click(function(){
			$('.modal-body').html($(this).attr("modalBody"));
			$('.modal-title').html($(this).attr("modalTitle"));
			docId = $(this).attr("docId");
			companyId = $(this).attr("companyId");
			action = "{{ route('companies.documents.filedelete') }}?_token={{ csrf_token() }}";
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
