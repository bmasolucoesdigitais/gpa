
@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')
<h1>{{__('general.Employee')}} - {{$employee->name}} - {{__('general.Allow')}}</h1>
@stop



@section('content')
@if($employee->pivot->file_auth != '')
@php
    $file = json_decode($employee->pivot->file_auth);
@endphp
@endif
<div class="col-md-12">

	<div class="box box-primary">


		<form id="edit_form" method="post" role="form" action="" enctype="multipart/form-data">
			@csrf
			<div class="box-body">


				<div class="form-group">
					<label></label>
					<input type="checkbox" id="allow" name="allow" value="1" @if($employee->manual == 1) checked @endif>  {{__('general.Allowed')}}
				</div>

				<div class="form-group">
					<label>{{__('general.Final date')}}</label>
					<input type="" id="dt_end" name="dt_end" class="form-control" placeholder="dd/mm/aaaa" value="@if($employee->dt_manual != null){{ date('d/m/Y', strtotime($employee->dt_manual))}}@endif">
                </div>
                <div>
                    @if(isset($file->file))
                    <h4><a href="/storage/uploads/{{ $file->file}}" target="_blank">Arquivo de Autorização</a></h4>
                    @endif
                   </div>
                <div class="form-group">
					<label>{{__('general.File')}}</label>
					<input type="file" accept=".jpg, .jpeg, .pdf, .png" id="file" name="file" class="form-control">
				</div>



				<div class="box-footer">
					<button type="" class="btn btn-primary salvar">{{__('general.Save')}}</button>
				</div>
			</div>
		</form>


	</div>


@section('js')
<script type="text/javascript">

$( document ).ready(function() {

	$('#dt_end').mask('00/00/0000', {reverse: false});



    $('.salvar').click(function(event){
        event.preventDefault();
        console.log($('#file').val() == '');
        if($('#file').val() == ''){
            alert('Favor escolher um arquivo para anexar.')
        }else{
            if($('#dt_end').val() ==''){
                alert('favor preencher a data.')
            }else{
              $('#edit_form').submit();
            }
        }
    });

});
</script>

@endsection

@endsection


<script src="/js/jquery_2.1.3_jquery.min.js"></script>
<script src="/js/twitter-bootstrap_3.3.1_js_bootstrap.min.js"></script>

