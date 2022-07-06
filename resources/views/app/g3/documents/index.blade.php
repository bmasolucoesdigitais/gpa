@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')
<h1>{{__('general.Documents')}}</h1>
<!-- metodo de entrada para translations {{ __('documents.test') }} -->
@stop

@section('content')


@canany('master','G3 Admin','G3 Edit' )
<a href="{{route('documents.add')}}" class="btn btn-app pull-right">
	<i class="fa fa-plus-square"></i> {{__('general.Add')}}
</a>
@endcanany

<div class="col-xs-12">
	<div class="box">

		<div class="box-body table-responsive ">
			<table id="datatable" class="table table-hover">
				<thead>

					<tr>


						<th>{{__('general.Name')}}</th>
						<th>{{__('general.Type')}}</th>
						<th>{{__('general.Description')}}</th>
						<th>{{__('general.Abaco Criteria')}}</th>
						<th>{{__('general.Print')}}</th>
						<th>{{__('general.Company')}}</th>
						@canany('master','G3 Admin','G3 Edit' )
						<th>{{__('general.Edit')}}</th>
						<th>{{__('general.Delete')}}</th>
						@endcanany
					</tr>
				</thead>
				<tbody>
					@foreach ($documents as $document)

					<tr>


						<td>{{ $document->name}}</td>
						<td>
							@if($document->type == 1) Empresa @endif
							@if($document->type == 2) Colaborador @endif
							@if($document->type == 3) Serviço @endif
						
						</td>
						<td>{{ $document->description}}</td>
						<td>{{ $document->fl_criteria}}</td>
						<td>
						@if ($document->fl_print)
								<i class="fa fa-fw fa-check-circle text-green"></i>
							@else
								<i class="fa fa-fw  fa-times-circle text-red"></i>
						@endif</td>
						<td>{{ $document->company->name}}</td>


@canany('master','G3 Admin','G3 Edit' )
						<td>
							<a class="	" href="{{route('documents.edit', $document->id)}}">

								<i class="fa fa-pencil-square" style="font-size: 32px"></i>

							</a>
						</td>
						<td> <a href="{{route('documents.delete', $document->id)}}">

							<i class="fa fa-trash-o text-red " style="font-size: 32px"></i>


						</a></td>
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
});
</script>


@endsection
@endsection
