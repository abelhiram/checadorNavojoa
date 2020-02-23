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
@extends('layouts.admin')
{!!Html::style('http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css')!!}

@section('pagina')
	Reportes
@stop

@section('contenido')

	@if(Session::has('message'))
		<?php $message=Session::get('message') ?>
		<div class="alert alert-success alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			{{Session::get('message')}}
		</div>
	@endif
	
	<div class="panel-group">
		<div class="panel panel-danger">
			<div class="panel-heading"><h4>Buscar</h4></div>
			<div class="panel-content">
				<div class="row top30 bot30">
					{!!Form::open(['route'=>'reportes', 'method'=>'GET', 'id'=>'formReportes', 'class'=>'form-block'])!!}
						<div class="col-md-12 col-md-offset-1">
							<div class="row">
								<div class="col-md-5 bot15">
									{!!Form::text('expediente',null,['class'=>'form-control','placeholder'=>'Número de empleado'])!!}	
								</div>
								<div class="col-md-5">
									{!!Form::text('nombre',null,['class'=>'form-control','placeholder'=>'Nombre del empleado'])!!}
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4  col-md-offset-4 top15">
								{!!Form::submit('Buscar',['class'=>'btn btn-primary btn-block','style'=>'margin-top:10px'])!!}
							</div>
						</div>
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
	
	<div class="tableInfo top30">
		<table class="table table-hover table-striped">
			<thead>
				<th><span>Activo</span></th>
				<th>No. Emp.</th>
				<th>Nombre</th>
				<th id="dato_extra">Nombramiento</th>
				<th id="dato_extra">Jornada</th>
			</thead>

			@foreach($personal as $personals)
				<tbody>
					<tr id="{{$personals->id}}">
						<td><span {!! $personals->modulo == '1' ? "class='label label-default' title='No Activo'" : "class='label label-success' title='Activo'" !!}>{!! $personals->modulo == '1' ? 'No activo' : 'Activo' !!}</span> </td>	
						<td>{{$personals->expediente}}</td>
						<td class="text-uppercase">{{$personals->nombre}}</td>
						<td id="dato_extra">{{$personals->nombramiento}}</td>
						<td id="dato_extra">{{$personals->jornada}}</td>
					</tr>
					
					<tr >
						<td colspan="5" class="">
							<div class="{{$personals->id}}" id="div_maestros">
								<div class="form-group">
									<div class="row">
										{!!Form::open(['route'=>['reporte.fechas', $personals->id], 'method'=>'GET', 'id'=>'{{$personals->id}}', 'class'=>'form-block'])!!}
											<div class="col-md-12 col-md-offset-1">
												<div class="col-md-5 top15">
													<h5>Fecha de inicio:</h5>
													{!!Form::date('fechaInicio', \Carbon\Carbon::now()->format('Y-m-d'),['class' => 'form-control'])!!}
												</div>

												<div class="col-md-5 top15">
													<h5>Fecha final:</h5>
													{!!Form::date('fechaFinal', \Carbon\Carbon::now()->format('Y-m-d'),['class' => 'form-control'])!!}
												</div>
													
												<div class="col-md-12 col-md-offset-4 mt-5 top15">
													<button class="btn btn-primary" type="submit">Generar reporte</button>
												</div>
											</div>
										{!! Form::close() !!}
									</div>
								</div>
							</div>	
						</td>
					</tr>		
					
				</tbody>
			@endforeach	
		</table>
	</div>

	<script type="text/javascript" src="{{ URL::asset('/js/reportes.js') }}"></script>
	<link rel="stylesheet" href="{{ URL::asset('/css/reportes.css') }}">

	{!!$personal->render()!!}
@endsection
