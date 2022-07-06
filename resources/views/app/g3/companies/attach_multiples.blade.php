
@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')
<h1>{{__('general.Company')}} - {{__('general.add')}}</h1>
@stop



@section('content')
<div class="col-md-12">

	<div class="box box-primary">


        @if(Request::is('g3/clients*'))
            <form method="post" role="form" action="">
        @else
            <form method="post" role="form" action="{{route('companies.attach')}}">
        @endif
			@csrf
			<input type="hidden" name="company" id="company" class=" form-control"  value="{{$data['owner']->id}}">
			<div class="box-body">


				<!-- <div class="form-group">
					<label>{{__('general.cnpj')}}</label>
					<input type="" name="cnpj" id="cnpj" class="form-control" placeholder="{{__('general.Input a text...')}}" value="">
				</div>
 -->
				<div class="form-group">
                <label>{{__('general.Companies')}}</label>
	               	<select multiple="multiple" name="clients[]" size="10" class="select2-responsive form-control" >
                   @foreach($data['companies'] as $company)
                   <option value="{{$company->id}}" @if($data['owner']->clients()->find($company->id)) selected @endif>{{$company->name}}</option>
                   @endforeach
                  </select>
     			 </div>

					<div class="box-footer">
						<button type="submit" class="btn btn-primary">{{__('general.Save')}}</button>
					</div>
				</div>

		</div>
	</form>
</div>
</div>
@section('js')
<script type="text/javascript">

$( document ).ready(function() {


	$(".select2-responsive").select2({
    	width: 'resolve' // need to override the changed default
	});
});
</script>


@endsection
@endsection
