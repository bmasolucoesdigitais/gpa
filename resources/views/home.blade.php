@extends('adminlte::page')

@section('title', 'Abaco Tecnologia - Dashboard')

@section('content_header')
<h1>Dashboard</h1>
@stop

@section('content')
@can('fornecedor')
<div class="col-md-4">

	<div class="box box-widget widget-user">
	
		<div class="widget-user-header bg-aqua-active">
			<h3 class="widget-user-username">{{$company->name}}</h3>
			<h5 class="widget-user-desc">E-Mail: {{$company->company_email}}</h5>
		</div>
		{{-- <div class="widget-user-image">
		<img class="img-circle" src="../dist/img/user1-128x128.jpg" alt="User Avatar">
		</div>
		<div class="box-footer"> --}}
			<div class="row">
				
				<div class="col-sm-6 border-right">
					<div class="description-block">
					<h5 class="description-header">{{$company->employees()->count()}}</h5>
					<span class="description-text">{{__('general.Employees')}}</span>
					</div>
				
				</div>
				
				<div class="col-sm-6 border-right">
					<div class="description-block">
						<h5 class="description-header">{{$company->services()->count()}}</h5>
						<span class="description-text">{{__('general.Scheduled services')}}</span>
					</div>
				
				</div>
			
			</div>
		
		</div>
	
	</div>
	</div>
	
	</div>
	<div class="col-xs-12">
		<div class="box">
			<div class="box-body">
				<h2>{{$company->name}}</h2>
				<p><strong>{{__('general.cnpj')}}:{{$company->cnpj}}</strong></p>
				<p><strong>{{__('general.E-Mail')}}:{{$company->company_email}}</strong></p>
			</div>
		</div>
	</div>
@endcan

@canany(['G3 Admin', 'cd'])
<div class="col-md-4">

	<div class="box box-widget widget-user">
	
		<div class="widget-user-header bg-aqua-active">
			<h3 class="widget-user-username">{{Auth::user()->name}}</h3>
			<h5 class="widget-user-desc">{{$company->name}}</h5>
		</div>
		{{-- <div class="widget-user-image">
		<img class="img-circle" src="../dist/img/user1-128x128.jpg" alt="User Avatar">
		</div>
		<div class="box-footer"> --}}
			<div class="row">
				
				<div class="col-sm-6 border-right">
					<div class="description-block">
					<h5 class="description-header">{{$data['stores_count']}}</h5>
					<span class="description-text">{{__('general.Stores')}}</span>
					</div>
				
				</div>
				
				<div class="col-sm-6 border-right">
					<div class="description-block">
						<h5 class="description-header">{{$data['companies_count']}}</h5>
						<span class="description-text">{{__('general.Companies')}}</span>
					</div>
				
				</div>
				
			
			</div>
		
		</div>
	
	</div>

<div class="col-md-4">

	<div class="box box-widget widget-user">
	
		<div class="widget-user-header bg-aqua-active">
			<h3 class="widget-user-username">Serviços</h3>
			<h5 class="widget-user-desc">Filtrados para sua região de atuação</h5>
		</div>
		{{-- <div class="widget-user-image">
		<img class="img-circle" src="../dist/img/user1-128x128.jpg" alt="User Avatar">
		</div>
		<div class="box-footer"> --}}
			<div class="row">
				
								
				<div class="col-sm-6 border-right">
					<div class="description-block">
						<h5 class="description-header">{{$data['services_count']-$data['services_to_aprove']}}</h5>
						<span class="description-text">Aprovados</span>
					</div>
				
				</div>
				<div class="col-sm-6 border-right">
					<div class="description-block">
						<a href="{{route('companies.servicesscheduled.listaprove')}}">
							<h5 class="description-header">{{$data['services_to_aprove']}}</h5>
							<span class="description-text">PARA APROVAR</span>
						</a> 
					</div>
				
				</div>
			
			</div>
		
		</div>
	
	</div>
</div>
</div>
	
{{-- </div>
	<div class="col-xs-12">
		<div class="box">
			<div class="box-body">
				<h2>{{$company->name}}</h2>
				<p><strong>{{__('general.cnpj')}}:{{$company->cnpj}}</strong></p>
				<p><strong>{{__('general.E-Mail')}}:{{$company->company_email}}</strong></p>
			</div>
		</div>
	</div> --}}
@endcanany
@can('tecnico')
<div class="col-md-4">

	<div class="box box-widget widget-user">
	
		<div class="widget-user-header bg-aqua-active">
			<h3 class="widget-user-username">{{Auth::user()->name}}</h3>
			<h5 class="widget-user-desc">{{$company->name}}</h5>
		</div>
		{{-- <div class="widget-user-image">
		<img class="img-circle" src="../dist/img/user1-128x128.jpg" alt="User Avatar">
		</div>
		<div class="box-footer"> --}}
			<div class="row">
				
				
				
				<div class="col-sm-12 border-right">
					<div class="description-block">
						<h5 class="description-header">{{__('general.E-Mail')}}</h5>
						<span class="description-text">{{Auth::user()->email}}</span>
					</div>
				
				</div>
				
			
			</div>
		
		</div>
	
	</div>

<div class="col-md-4">

	<div class="box box-widget widget-user">
	
		<div class="widget-user-header bg-aqua-active">
			<h3 class="widget-user-username">Serviços</h3>
			<h5 class="widget-user-desc">Filtrados para sua região de atuação</h5>
		</div>
		{{-- <div class="widget-user-image">
		<img class="img-circle" src="../dist/img/user1-128x128.jpg" alt="User Avatar">
		</div>
		<div class="box-footer"> --}}
			<div class="row">
				
								
				<div class="col-sm-6 border-right">
					<div class="description-block">
						<h5 class="description-header">{{$data['services_count']-$data['services_to_aprove']}}</h5>
						<span class="description-text">Aprovados</span>
					</div>
				
				</div>
				<div class="col-sm-6 border-right">
					<div class="description-block">
						<a href="{{route('companies.servicesscheduled.listaprove')}}">
							<h5 class="description-header">{{$data['services_to_aprove']}}</h5>
							<span class="description-text">PARA APROVAR</span>
						</a> 
					</div>
				
				</div>
			
			</div>
		
		</div>
	
	</div>
</div>
</div>
	
{{-- </div>
	<div class="col-xs-12">
		<div class="box">
			<div class="box-body">
				<h2>{{$company->name}}</h2>
				<p><strong>{{__('general.cnpj')}}:{{$company->cnpj}}</strong></p>
				<p><strong>{{__('general.E-Mail')}}:{{$company->company_email}}</strong></p>
			</div>
		</div>
	</div> --}}
@endcan
{{-- <pre><p>You are logged in!</p>
</pre> --}}
	

@endsection
@section('js')
@can('fornecedor')
	

	<script type="text/javascript">
		$(document).ready(function(){
			email = '{{$company->company_email}}';
			$('.modal-title').html("Atualização de e-mail");
			$('.modal-body').html('<div >'
						+'<form id="add_form" method="post" role="form" action="">'
							+'<div class="form-group">'
								+'<label for="mail">E-Mail</label>'
								+'<input type="text" id="mail" name="mail" class="form-control" value="{{$company->company_email}}">'
							+'</div>'
						+'</form>'
						
					+'</div>');
			if(!email){
				$('#confirmationModal').modal('show');
			}
			
			$('#deleteConfirm').click(function(){
				var data= {email:$('#mail').val(), id: {{$company->id}}};
				action = "{{ route('companies.emailupdate') }}?_token={{ csrf_token() }}";
				console.log(data);
				//console.log(action);

				jQuery.ajax({
					type: "POST",
					url: action,
					data: data,
					success: function(data) {

						if (data == 1) {
					        // console.log(data);
					    	alert('E-mail atualizado!')
							$('#confirmationModal').modal('hide');
					        location.reload();

					    }else{
					    	alert('Houve um problema ao atualizar o e-mail; Contate o administrador!')
					    }
					}
				});
				
			});
			
		});
		$("#button").click(function(){
		});
	</script>
	@endcan
@endsection