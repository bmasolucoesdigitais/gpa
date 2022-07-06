
@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')
<h1>{{ __('general.Scheduled services') }} - APR - Análise Preliminar de Risco</h1>
@stop

@section('content')

<a href="{{route('companies.servicesscheduled.list')}}" class="btn btn-app pull-right">
    <i class="fa fa-arrow-left"></i> {{__('general.Companies')}}
</a>


<div class="col-md-12">

	<div class="box box-primary">


		<form id="add_form" method="post" role="form" action="#" enctype="multipart/form-data">
			@csrf
			<div class="box-body">

				<div class="form-group">
					<label></label>
					<h4 >{{__('general.Service')}}: {{$service->service}}</h4>
				</div>
				
				
					<div class="col-xs-12">
					<div class="form-group">
							<label>APR Assinada</label>
							<input rows="" class="form-control" type="file" name="file" value="">
					</div>
				</div>
					<div class="col-xs-12">
							
						<div class="box-footer">
							<button id="btn_send" type="submit" class="btn btn-primary">{{__('general.Save')}}</button>
						</div>
					</div>
				</div>

		</div>
	</form>
</div>
</div>
@section('js')




<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>


@endsection

@endsection


<script src="/js/jquery_2.1.3_jquery.min.js"></script>
<script src="/js/twitter-bootstrap_3.3.1_js_bootstrap.min.js"></script>

<!-- Laravel Javascript Validation -->

{{-- {!! $validator->selector('#add_form') !!} --}}
