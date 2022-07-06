@extends('adminlte::prova')

@section('title', 'Abaco Tecnologia')

@section('content_header')


<h1>Treinamento: {{$training->name}}</h1>


@stop

@section('content')


{{-- 
@canany(['master', 'G3 Admin'])
<a href="{{route('tests.add')}}" class="btn btn-app pull-right">
	<i class="fa fa-plus-square"></i> {{__('general.Add')}}
</a>
@endcanany --}}


<div class="col-xs-12">
	<div class="box">
        <div class="box-header">
            <h2>Prova: {{$training->test->name}}
                @if ($student->status_test == 1)
                <span style="float:right;">Tentativa: 
                    1
                    / 2</span></h2>
                    @endif
                    @if ($student->status_test == 3)
                    <span style="float:right;">Tentativa: 
                    2
                    / 2</span></h2>
                    <h3 class="text-red">Obs. Reprovado na primeira tentativa</h3>
                @endif
        </div>
        <div class="box-body">
            @if ($student->status_test == 1 || $student->status_test == 3)
                
                <form action="" role="form" method="POST">
                    @csrf
                    @foreach ($training->test->quests as $quest)
                        
                        <h3>{{$quest->question}}</h3>
                        <div class="form-group">
                        @for ($i = 0; $i < 10; $i++)
                            @php
                                $resp = 'answer'.$i;
                            @endphp
                            @if ($quest->$resp != '')
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="resp[{{$quest->id}}]" value="{{$i}}">
                                    <label class="form-check-label"><h4>{{$quest->$resp}}</h4></label>
                                </div>
                            @endif
                            
                        @endfor
                        </div>
                        
                    @endforeach
                    <div class="box-footer">
                        <input type="submit" value="Enviar" class="btn btn-primary">
                    </div>
                </form>
            @else
            <h2>Status: 
                @if ($student->status_test == 2)
                <span class="text-green"> 
                    Aprovado na primeira tentativa
                </span>
                @endif
                @if ($student->status_test == 4)
                <span class="text-green"> 
                    Aprovado na segunda tentativa
                </span>
                @endif
                @if ($student->status_test == 5)
                <span class="text-red"> 
                    Reprovado na segunda tentativa!
                    Procure seu gestor para refazer o treinamento
                </span>
                @endif
            </h2>
            <h2 class="text-red">Não é possivel fazer a prova</h2>

            @endif
        </div>		
    </div>

</div>
@section('js')
<script type="text/javascript">


	$( document ).ready(function() {

       
	});

</script>


@endsection
@endsection
