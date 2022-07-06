@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')


@can('master')
<h1>{{ __('general.Trainings') }}</h1>
@else
    <h1>{{ __('general.Trainings') }}</h1>
@endcan


@stop

@section('content')



@canany(['master', 'G3 Company Edit'])
<a href="{{route('trainingschedule.add')}}" class="btn btn-app pull-right">
	<i class="fa fa-plus-square"></i> {{__('general.Add')}}
</a>
@endcanany


<div class="col-xs-12">
	<div class="box">


		<div class="box-body table-responsive">
			<table id="datatable" class="table table-hover table-striped">
				<thead><tr>


					<th>{{__('general.Name')}}</th>
					<th>{{__('general.Company')}}</th>
					<th>{{__('general.Init')}}</th>
					<th>{{__('general.End')}}</th>
					<th>{{__('general.Vacancies')}}
					<th>{{__('general.Employees')}}
					<th>{{__('general.Accomplished')}}
					<th>{{__('general.Reserve')}}
				</tr>
			</thead>
			<tbody>
            @foreach ($trainings as $training)

				<tr>


					<td>{{ $training->name}}</td>
					<td>{{ $training->company->name}}</td>
					<td>{{preg_replace('#(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2})#', '$3/$2/$1 $4:$5', $training->dt_ini)}}</td>
					<td>{{preg_replace('#(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2})#', '$3/$2/$1 $4:$5', $training->dt_end)}}</td>
					<td>{{ $training->vacancies - $training->students()->count()}}</td>
					<td>
                        <a class="" href="{{route('provider.trainingreserve.employees', $training->id )}}">
                            {{ $training->students()->whereIn('employee_id', $company->employees()->pluck('id')->toArray())->count()}}</td>
                        </a>
					<td>
                        @if ($training->fl_accomplished == 1)
                                <i class="fa fa-check text-green"  style="font-size: 32px"></i>
                            @else
                                <i class="fa fa-times text-red"  style="font-size: 32px"></i>
                        @endif
                        {{ $training->acco}}
                    </td>


                    <td>
                        @if ($training->vacancies - $training->students()->count() > 0)
                        <a class="" href="{{route('provider.trainingreserve.attach', $training->id )}}">

                            <i class="fa fa-plus-square text-green" style="font-size: 32px"></i>

                        </a>

                        @endif

                    </td>



                </tr>

	        @endforeach

</tbody></table>
</div>

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
