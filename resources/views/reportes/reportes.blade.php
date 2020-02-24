@extends('layouts.admin')
@section('contenido')	
<?php use App\Http\Controllers\reportesController;
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
?>

{!!Html::style('http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css')!!}

	@if(Session::has('message'))
	<?php $message=Session::get('message') ?>
		<div class="alert alert-success alert-dismissible" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		{{Session::get('message')}}
		</div>
	@endif
	{!!Form::open(['route'=>'rep', 'method'=>'GET'])!!}
	<div class="form-group">
		{!!Form::date('fechaInicio', \Carbon\Carbon::now()->format('Y-m-d'),['class' => 'form-control'])!!}
	</div>
	<div class="form-group">
		{!!Form::date('fechaFinal', \Carbon\Carbon::now()->format('Y-m-d'),['class' => 'form-control'])!!}
	</div>
	<div class="form-group">
		{!!Form::submit('Buscar',['class'=>'btn btn-primary'])!!}
	</div>
	{!!Form::close()!!}
	
	<table class="table table-hover table-striped">
	<thead>
		<th>No. Emp.</th>
		<th>Nombre</th>
		<th>Retardos</th>
		<th>Comisión</th>
		<th>Día económico</th>
		<th>Inasistencias</th>
		<th>Incapacidad</th>
		<th>Omisión de checada</th>
		<th>Canje de tiempo extra</th>	
		<th>Comentario</th>
	</thead>
	@foreach($personal as $personals)
	<tbody>
		<td>{{$personals->expediente}}</td>
		<td>{{$personals->nombre}}</td>
		<td>{{reportesController::faltas($personals->id,2,$fechainicio,$fechaFinal)}}</td>
		<td>{{reportesController::faltas($personals->id,8,$fechainicio,$fechaFinal)}}</td>
		<td>{{reportesController::faltas($personals->id,7,$fechainicio,$fechaFinal)}}</td>
		<td>{{reportesController::faltas($personals->id,3,$fechainicio,$fechaFinal)}}</td>
		<td>{{reportesController::faltas($personals->id,4,$fechainicio,$fechaFinal)}}</td>
		<td>{{reportesController::faltas($personals->id,5,$fechainicio,$fechaFinal)}}</td>
		<td>{{reportesController::faltas($personals->id,6,$fechainicio,$fechaFinal)}}</td>
		<td>{{reportesController::comentarios($personals->id,$fechainicio,$fechaFinal)}}</td>
	</tbody>
	@endforeach	
	</table>			
	{!!$personal->render()!!}
@endsection