@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')
<h1>{{__('general.Reports')}} - {{__('general.Aprove')}}</h1>
<!-- metodo de entrada para translations {{ __('documents.test') }} -->
@stop

@section('content')





<div class="col-xs-12">
	<div class="box">

		<div class="box-body table-responsive ">
			<table id="datatable" class="table table-hover">
				<thead>
					<tr>

						<th>{{__('general.Description')}}</th>
						<th>{{__('general.Expires')}}</th>
						<th>{{__('general.Document')}}</th>
						<th>{{__('general.Company')}}</th>
						<th>{{__('general.Added')}}</th>
						<th>{{__('general.Employee')}}</th>
						<th>{{__('general.Company')}}</th>
						<th>{{__('general.Edit')}}</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($delivereds as $delivered)

					<tr>


						<td>{{ $delivered->description}}</td>
						<td>{{ date('d/m/Y', strtotime($delivered->expiration))}}</td>
						<td>{{ $delivered->document->name}}</td>
						<td>@if($delivered->company){{ $delivered->company->name}}@endif</td>
						<td>{{ $delivered->created_at}}</td>
						<td>@if($delivered->employee){{ $delivered->employee->name}}@endif</td>
						<td>@if(isset($delivered->employee->companies[0])){{ $delivered->employee->companies[0]->name}}@endif</td>



						<td>
                            @if ( $delivered->employee != null)
                            @php
                                $isService = false;
                                $serviceId = 0;
                                foreach($delivered->employee->services as $service){
                                    if($service->documents->find($delivered->document_id)){
                                        $isService = true;
                                        $serviceId = $service->id;
                                    }
                                }
                            @endphp
                                @if($isService == true)
							        <a class="	" href="{{route('employees.delivereds', [$delivered->employee_id, $serviceId])}}">
                                @else
                                <a class="	" href="{{route('employees.documents', $delivered->employee_id)}}">
                                @endif

                            @else

							<a class="	" href="{{route('companies.documents', $delivered->company_id)}}">
                            @endif

								<i class="fa fa-pencil-square" style="font-size: 32px"></i>

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
		});
	</script>


	@endsection
	@endsection
