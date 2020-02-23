@extends('layouts.admin')
{!!Html::style('http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css')!!}
@section('pagina')
@foreach($personal as $personal)
		Expediente:
		{{$personal->expediente}}
		Nombre:
		{{$personal->nombre}}
	@endforeach
@stop

@section('contenido')	
	@if(Session::has('message'))
	<?php $message=Session::get('message') ?>
		<div class="alert alert-success alert-dismissible" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		{{Session::get('message')}}
		</div>
	@endif
	<div class="col-md-12" >
		{!!Form::open(['route'=>'horarios', 'method'=>'GET','class' => ''])!!}
		
		<div class="col-md-2">
			
			{!!Form::select('dia',['1' => 'Lunes','2'=>'Martes','3'=>'Miercoles','4'=>'Jueves','5'=>'Viernes','6'=>'Sábado','7'=>'Domingo'],null, ['class' => 'form-control'])!!}
		</div>
		{!!Form::text('id', $personal->id ,['style' => 'display:none;'])!!}
		<div class="col-md-2">
			{!!Form::submit('Buscar',['class'=>'btn btn-primary'])!!}
		</div>
		<div class="col-md-12" style="margin-top:10px;">
			{!!link_to_route('horarios.crear', $title = 'Crear nuevo', $parameters = $personal->id, $attributes = ['class'=>'btn btn-success']);!!}		
			{!!link_to_route('checadas.show', $title = 'checadas', $parameters = $personal->id, $attributes = ['class'=>'btn btn-primary','style'=>'margin-left:15px;']);!!}
			{!!link_to_route('personal', $title = 'Regresar', $parameters = null, $attributes = ['class'=>'btn btn-warning','style'=>'margin-left:15px;']);!!}	
			{!!Form::close()!!}
		</div>
	</div>
	
	@foreach($horario as $horario)
<div class="col-lg-3 col-xs-5">
	<div class="box" style="margin-top:10px;">
          <!-- small box -->
          <div class="small-box bg-yellow ">
            	<div class="inner">
              		<h3>	
			  		@if($horario->dia==1)
					Lunes
					@endif
					@if($horario->dia==2)
					Martes
					@endif
					@if($horario->dia==3)
					Miercoles
					@endif
					@if($horario->dia==4)
					Jueves
					@endif
					@if($horario->dia==5)
					Viernes
					@endif
					@if($horario->dia==6)
					Sábado
					@endif
					@if($horario->dia==7)
					Domingo
					@endif 
					</h3>

					<h4>Hora de entrada: {{$horario->hora_entrada}}<br>
					Hora de salida: {{$horario->hora_salida}}</h4>
            	</div>
			{!!link_to_route('horarios.edit', $title = 'Editar', $parameters = $horario->id, $attributes = ['class'=>'small-box-footer']);!!}
          	</div>
        </div>
	</div>
	@endforeach	
</div>	
@endsection