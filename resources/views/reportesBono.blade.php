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
{!!Html::style('http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css')!!}
@section('pagina')
Reportes bono
@stop
@section('contenido')	
	@if(Session::has('message'))
	<?php $message=Session::get('message') ?>
		<div class="alert alert-success alert-dismissible" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		{{Session::get('message')}}
		</div>
	@endif
	{!!Form::open(['route'=>'reportesBono', 'method'=>'GET'])!!}
	<div class="col-md-8">
		<div class="col-md-6">
			Fecha de inicio
			{!!Form::date('fechaInicio', \Carbon\Carbon::now()->format('Y-m-d'),['class' => 'form-control'])!!}
			Fecha final
			{!!Form::date('fechaFinal', \Carbon\Carbon::now()->format('Y-m-d'),['class' => 'form-control'])!!}
		</div>
		<div class="col-md-6">
			Expediente
			{!!Form::text('expediente',null,['class'=>'form-control','placeholder'=>'ingresa el número de empleado'])!!}	
			Nombre
			{!!Form::text('nombre',null,['class'=>'form-control','placeholder'=>'ingresa el nombre del empleado'])!!}
		</div>
		<div class="col-md-6">
			{!!Form::submit('Buscar',['class'=>'btn btn-primary btn-block','style'=>'margin-top:10px'])!!}
		</div>
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