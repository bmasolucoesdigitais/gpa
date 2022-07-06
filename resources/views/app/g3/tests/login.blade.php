@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')''


    <h1>{{ __('general.Test') }}</h1>


@stop

@section('content')


@canany(['master', 'G3 Admin'])
<a href="{{route('tests.add')}}" class="btn btn-app pull-right">
	<i class="fa fa-plus-square"></i> {{__('general.Add')}}
</a>
@endcanany


<div class="col-xs-12">
    @if(isset($cpfValid))
        @if(!$cpfValid)
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fa fa-times"></i> Erro!</h5>
                CPF Inválido!
            </div>
        @endif
    @endif
    
    <div class="box">
        
        
        <div class="box-body">
            <div class="form-group">
                <label for="cpf">CPF</label>
                <input type="text" name="cpf" id="cpf" class="form-control">

            </div>
        </div>
        <div class="box-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i></button>
        </div>

</div>

</div>
@section('js')
<script type="text/javascript">


	$( document ).ready(function() {
        $('#cpf').mask('000.000.000-00', {reverse: true});

        token = '{{$student->token}}'
        $('.btn').click(function(){
            event.preventDefault();
            cpf = $('#cpf').val();
            console.log(cpf, token);
            window.location.href = '/prova/'+token+'/'+cpf+'/';
        });

        
	});

</script>


@endsection
@endsection
