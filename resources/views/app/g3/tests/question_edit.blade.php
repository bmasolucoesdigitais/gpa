
@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')
<h1>{{__('general.Tests')}} - {{$test->name}} - {{__('general.Question')}} - {{__('general.Edit')}}</h1>
@endsection



@section('content')
<div class="col-md-12">

    <div class="box box-primary">

        <form id="add_form" method="post" role="form" action="">
            @csrf

            <div class="box-body">

                <div class="form-group">
                    <label>{{__('general.Question')}}</label>
                    <input type="" name="question" class="form-control" placeholder="Digite um texto..." value="{{$question->question}}">
                </div>

                <div class="form-group">
                    <label>{{__('general.Answer')}} 1</label>
                    <input type="" name="answer1" class="form-control" placeholder="Digite um texto..." value="{{$question->answer1}}">
                </div>

                <div class="form-group">
                    <label>{{__('general.Answer')}} 2</label>
                    <input type="" name="answer2" class="form-control" placeholder="Digite um texto..." value="{{$question->answer2}}">
                </div>

                <div class="form-group">
                    <label>{{__('general.Answer')}} 3</label>
                    <input type="" name="answer3" class="form-control" placeholder="Digite um texto..." value="{{$question->answer3}}">
                </div>

                <div class="form-group">
                    <label>{{__('general.Answer')}} 4</label>
                    <input type="" name="answer4" class="form-control" placeholder="Digite um texto..." value="{{$question->answer4}}">
                </div>

                <div class="form-group">
                    <label>{{__('general.Answer')}} 5</label>
                    <input type="" name="answer5" class="form-control" placeholder="Digite um texto..." value="{{$question->answer5}}">
                </div>

                <div class="form-group">
                    <label>{{__('general.Answer')}} 6</label>
                    <input type="" name="answer6" class="form-control" placeholder="Digite um texto..." value="{{$question->answer6}}">
                </div>

                <div class="form-group">
                    <label>{{__('general.Answer')}} 7</label>
                    <input type="" name="answer7" class="form-control" placeholder="Digite um texto..." value="{{$question->answer7}}">
                </div>

                <div class="form-group">
                    <label>{{__('general.Answer')}} 8</label>
                    <input type="" name="answer8" class="form-control" placeholder="Digite um texto..." value="{{$question->answer8}}">
                </div>

                <div class="form-group">
                    <label>{{__('general.Answer')}} 9</label>
                    <input type="" name="answer9" class="form-control" placeholder="Digite um texto..." value="{{$question->answer9}}">
                </div>

                <div class="form-group">
                    <label>{{__('general.Answer')}} 10</label>
                    <input type="" name="answer10" class="form-control" placeholder="Digite um texto..." value="{{$question->answer10}}">
                </div>

                <div class="form-group">
                    <label>{{__('general.Type')}} 10</label>
                    <select  name="type" class="form-control" >
                        <option value="1" @if($question->type == 1) selected @endif>SHE</option>
                        <option value="2" @if($question->type == 2) selected @endif>Qualidade</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>{{__('general.Correct Answer')}}</label>
                    <select name="correct_answer" id="correct_answer">
                        <option value="1" @if($question->correct_answer == 1) selected @endif>1</option>
                        <option value="2" @if($question->correct_answer == 2) selected @endif>2</option>
                        <option value="3" @if($question->correct_answer == 3) selected @endif>3</option>
                        <option value="4" @if($question->correct_answer == 4) selected @endif>4</option>
                        <option value="5" @if($question->correct_answer == 5) selected @endif>5</option>
                        <option value="6" @if($question->correct_answer == 6) selected @endif>6</option>
                        <option value="7" @if($question->correct_answer == 7) selected @endif>7</option>
                        <option value="8" @if($question->correct_answer == 8) selected @endif>8</option>
                        <option value="9" @if($question->correct_answer == 9) selected @endif>9</option>
                        <option value="10" @if($question->correct_answer == 10) selected @endif>10</option>
                    </select>
                </div>







                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">{{__('general.Save')}}</button>
                </div>
            </div>
        </form>


    </div>
    @section('js')
    <script type="text/javascript">

        $( document ).ready(function() {
            $(".select2-responsive").select2({
                width: 'resolve' // need to override the changed default
            });
        });
    </script>

    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

    @endsection

@endsection


<script src="/js/jquery_2.1.3_jquery.min.js"></script>
<script src="/js/twitter-bootstrap_3.3.1_js_bootstrap.min.js"></script>

<!-- Laravel Javascript Validation -->

{!! $validator->selector('#add_form') !!}
