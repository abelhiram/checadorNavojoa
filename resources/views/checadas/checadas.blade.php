<?php use App\Http\Controllers\checadasController;
if(!isset($_GET['fechaInicio'])){
	$fechainicio = date("Y-m-d");
} else{
	$fechainicio = $_GET['fechaInicio'];
}

if(!isset($_GET['fechaFinal'])){
	$fechaFinal = date("Y-m-d");
} else{
	$fechaFinal = $_GET['fechaFinal'];
}
if(!isset($_GET['id'])){
	$id = date("1");
} else{
	$id = $_GET['id'];
}
?>
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
	
	<div class="col-md-8">
		{!!Form::open(['route'=>'checadas', 'method'=>'GET','class' => ''])!!}
		
		<div class="col-md-6">
			Fecha de inicio
			{!!Form::date('fechaInicio', \Carbon\Carbon::now()->format('Y-m-d'),['class' => 'form-control'])!!}
			Fecha final
			{!!Form::date('fechaFinal', \Carbon\Carbon::now()->format('Y-m-d'),['class' => 'form-control'])!!}
		</div>
	
		<div class="col-md-3">
			{!!Form::submit('Buscar',['class'=>'btn btn-primary btn-block','style'=>'margin-top:20px'])!!}
		</div>
		<div class="col-md-12">
			{!!link_to_route('checadas.crear', $title = 'Registrar checada', $parameters = $personal->id, $attributes = ['class'=>'btn btn-success','style'=>'margin-top:10px;']);!!}		
			{!!link_to_route('horarios.show', $title = 'Horario', $parameters = $personal->id, $attributes = ['class'=>'btn btn-primary','style'=>'margin-top:10px;margin-left:10px']);!!}
			{!!link_to_route('personal', $title = 'Regresar', $parameters = null, $attributes = ['class'=>'btn btn-warning','style'=>'margin-top:10px;margin-left:10px']);!!}
		</div>
	</div>
	<div style="display:none;">
			{!!Form::text('id', $personal->id ,['class' => 'form-control'])!!}
			{!!Form::close()!!}
			</div>
		<table class="table">
		<thead>
			<th>Hora de entrada</th>
			<th>Entrada</th>
			<th>Hora de salida</th>
			<th>Salida</th>
			<th>Permiso por horas inicio</th>
			<th>Permiso por horas fin</th>
			<th>Comentario</th>
			<th>Fecha</th>
		</thead>
		@foreach($checada as $checadas)
		<tbody>
			<td>{{$checadas->hora}}</td>
			@if($checadas->checada!=null)
				@if($checadas->checada==0)
				<td>Entrada con bono</td>
				@endif
				@if($checadas->checada==1)
				<td>Asistencia</td>
				@endif
				@if($checadas->checada==2)
				<td>Retardo</td>
				@endif
				@if($checadas->checada==3)
				<td>Inasistencia</td>
				@endif
				@if($checadas->checada==4)
				<td>Incapacidad</td>
				@endif
				@if($checadas->checada==5)
				<td>Omision de checada</td>
				@endif
				@if($checadas->checada==6)
				<td>Canje de tiempo extra</td>
				@endif
				@if($checadas->checada==7)
				<td>Día económico</td>
				@endif
				@if($checadas->checada==8)
				<td>Comisión</td>
				@endif
				@if($checadas->checada==11)
				<td>Permiso por horas inicio</td>
				@endif
				@if($checadas->checada==12)
				<td>Justificación: día festivo</td>
				@endif	
			@else
				<td>Con bono</td>			
			@endif
			<td>{{$checadas->hora_salida}}</td>
			@if($checadas->checada_salida!=null)	
				
				@if($checadas->checada_salida==1)
				<td>Salida normal</td>
				@endif
				@if($checadas->checada_salida==2)
				<td>Salida anticipada</td>
				@endif
				@if($checadas->checada_salida==5)
				<td>Omisión de salida</td>
				@endif
				@if($checadas->checada==11)
				<td>Permiso por horas fin</td>
				@endif
				@if($checadas->checada==12)
				<td>Justificación: día festivo</td>
				@endif	
			@else
			<td></td>
			@endif
			<td>{{$checadas->entradaHoras}}</td>
			<td>{{$checadas->salidaHoras}}</td>
			<td>{{$checadas->comentario}}</td>
			<td>{{$checadas->fecha}}</td>
			<td>
				{!!link_to_route('checadas.edit', $title = 'Editar', $parameters = $checadas->id, $attributes = ['class'=>'btn btn-success']);!!}
			</td>
		</tbody>
		@endforeach	
		</table>
		{!!$checada->render()!!}
	
	@endsection