<?php use App\Http\Controllers\reportesController;?>
@extends('layouts.admin')
{!!Html::style('http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css')!!}
@section('contenido')	
	@if(Session::has('message'))
	<?php $message=Session::get('message') ?>
		<div class="alert alert-success alert-dismissible" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		{{Session::get('message')}}
		</div>
	@endif
	{!!Form::open(['route'=>'reportes', 'method'=>'GET'])!!}
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
		<td>{{reportesController::faltas($personals->id,2)}}</td>
		<td>{{reportesController::faltas($personals->id,8)}}</td>
		<td>{{reportesController::faltas($personals->id,7)}}</td>
		<td>{{reportesController::faltas($personals->id,3)}}</td>
		<td>{{reportesController::faltas($personals->id,4)}}</td>
		<td>{{reportesController::faltas($personals->id,5)}}</td>
		<td>{{reportesController::faltas($personals->id,6)}}</td>
		<td>{{reportesController::comentarios($personals->id)}}</td>
	</tbody>
	@endforeach	
	</table>			
	{!!$personal->render()!!}
@endsection