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



@canany(['master', 'G3 Admin'])
<a href="{{route('tests.add')}}" class="btn btn-app pull-right">
	<i class="fa fa-plus-square"></i> {{__('general.Add')}}
</a>
@endcanany


<div class="col-xs-12">
	<div class="box">


		<div class="box-body table-responsive">
			<table id="datatable" class="table table-hover table-striped">
				<thead><tr>


					<th>{{__('general.Name')}}</th>
					@can('master')<th>{{__('general.Company')}}</th>@endcan
					<th>{{__('general.Questions')}}</th>
					<th>{{__('general.Questions Total')}}</th>
					<th>{{__('general.Minutes')}}</th>
					<th>{{__('general.View')}}{{__('general.Questions')}}</th>
					@can('master')<th>{{__('general.Edit')}}</th>@endcan
					@can('master')<th>{{__('general.Delete')}}</th>@endcan
				</tr>
			</thead>
			<tbody>
            @foreach ($tests as $test)

				<tr>


					<td>{{ $test->name}}</td>
					@can('master')<td>{{ $test->company->name}}</td>@endcan
					<td>{{ $test->questions}}</td>
					<td>{{ $test->quests->where('fl_deleted', 0)->count()}}</td>
					<td>{{ $test->minutes}}</td>
                    <td>
                        <a class="" href="{{route('tests.questions', $test->id )}}">

                            <i class="fa fa-list text-blue" style="font-size: 32px"></i>

                        </a>
                    </td>

                    @can('master')
                    <td>
                        <a class="" href="{{route('tests.edit', $test->id )}}">

                            <i class="fa fa-pencil text-green" style="font-size: 32px"></i>

                        </a>
                    </td>
                        <td>
                            <a
                            id="btnDeleteTraining"
							modalTitle="{{ __('general.Delete confirmation') }}"
							modalBody="{{  __("general.Are you sure that you want to delete this ") }} {{ __("general.test")  }}?"
							href=""
							data-toggle="modal"
							data-target="#confirmationModal"
							idTr="{{ $test->id }}"
							class="btnDeleteTraining" >

                            <i class="fa fa-trash-o text-red " style="font-size: 32px"></i>
                            </a>
                        </td>
                    @endcan


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

        $('.btnDeleteTraining').click(function(){
            console.log('TESTE');
            console.log($(this));
			$('.modal-body').html($(this).attr("modalBody"));
			$('.modal-title').html($(this).attr("modalTitle"));
			id = $(this).attr("idTr");
			tk = '{{ csrf_token() }}';
			action = "{{ route('tests.delete') }}";
        });

        $('#deleteConfirm').click(function(){
            //$('.modal-body').append("Confirmar a exclusão deste arquivo"+ $(this).attr("file"));
            //alert(fileId);
            $('#confirmationModal').modal('hide');
            var data= {id:id, _token:tk};

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

        $('.changeAccomplished').click(function (){
        event.preventDefault();
        target = $(this);
        console.log("change accomplished");
        var id = $(this).attr('data-id');
        console.log(id);
        tk = '{{ csrf_token() }}';
        var data = {id:id, _token:tk};
        jQuery.ajax({
            type: "POST",
            url: "/g3/tests/delete",
            data: data,
            success: function(data) {

                if (data == 1) {
                    // console.log(data);
                    $(target).html('<i class="fa fa-check text-green" style="font-size: 32px"></i>');
                }else if(data == 0){
                    $(target).html('<i class="fa fa-times text-red" style="font-size: 32px"></i>');
                }else{
                    alert('Erro ao mudar status');
                }
            }
        });

        });
	});

</script>


@endsection
@endsection
