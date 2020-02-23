<?php use App\Http\Controllers\reportesBono;
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
@extends('layouts.admin')
{!!Html::style('https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css')!!}
@section('contenido')	
	@if(Session::has('message'))
	<?php $message=Session::get('message') ?>
		<div class="alert alert-success alert-dismissible" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		{{Session::get('message')}}
		</div>
	@endif
	{!!Form::open(['route'=>'reportesBono', 'method'=>'GET'])!!}
	<div class="form-group">
		{!!Form::date('fechaInicio', \Carbon\Carbon::now()->format('Y-m-d'),['class' => 'form-control'])!!}

		
		{!!Form::text('expediente',null,['class'=>'form-control','placeholder'=>'ingresa el número de empleado'])!!}
	</div>
	<div class="forma">
		{!!Form::date('fechaFinal', \Carbon\Carbon::now()->format('Y-m-d'),['class' => 'form-control'])!!}

		{!!Form::text('nombre',null,['class'=>'form-control','placeholder'=>'ingresa el nombre del empleado'])!!}
	</div>
	<div class="form-group">
		{!!Form::submit('Buscar',['class'=>'btn btn-primary'])!!}
	</div>
		
	
	<table class="table table-hover table-striped">
	<thead>
		<th>No. Emp.</th>
		<th>Nombre</th>		
	</thead>
	@foreach($personal as $personals)
	<tbody>
		<td>{{$personals->expediente}}</td>
		<td>{{$personals->nombre}}</td>
	</tbody>
	@endforeach	
	</table>			
	{!!$personal->render()!!}
@endsection