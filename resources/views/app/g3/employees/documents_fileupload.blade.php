@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')
<h1>{{__('general.Employees')}} - {{$data['employee']->name}} - {{__('general.Documents')}} - {{__('general.Upload')}}</h1>

@stop

@section('content')

<a href="{{route('employees.documents', $data['employee']->id)}}" class="btn btn-app pull-right">
	<i class="fa fa-arrow-left"></i> {{__('general.Documents')}}
</a>

<div class="col-xs-12">
	<div class="box">
		<div class="box-body table-responsive">
			<div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">{{__('general.Upload for')." ". $data['delivered']->description}}</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
           <form id="add_form" action="" method="post" enctype="multipart/form-data">
           	@csrf
              <div class="box-body">

              	<div class="form-group">
                  <label for="exampleInputEmail1">{{__('general.Name')}}</label>
                  <input type="text" class="form-control" id="name" name="name" placeholder="{{__('general.Name')}}">
                </div>
                <div class="form-group">
                  <label for="file">{{__('general.File').":"}}</label>
                   <input type="file" accept=".jpg, .jpeg, .pdf, .png" id="file" name="file">

                  <!--<p class="help-block">Example block-level help text here.</p>-->
                </div>
                
             
    			
    			<input type="hidden" name="delivered" value="{{$data['delivered']->id}}">
    			<input type="hidden" name="employee" value="{{$data['employee']->id}}">

              <div class="box-footer">
              	<button type="submit" class="btn btn-primary">{{__('general.Save')}}</button>
                
              </div>
            </form>
          </div>
			
	
    
  
</form>
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

<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

@endsection

@endsection


<script src="/js/jquery_2.1.3_jquery.min.js"></script>
<script src="/js/twitter-bootstrap_3.3.1_js_bootstrap.min.js"></script>

<!-- Laravel Javascript Validation -->

{!! $validator->selector('#add_form') !!}