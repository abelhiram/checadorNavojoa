<?php use App\Http\Controllers\reportesController;
if(!isset($_GET['fechaInicio'])){
	$fechaInicio = date("Y-m-d");
} else{
	$fechaInicio = $_GET['fechaInicio'];
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
								<div class="col-md-5 ">
									{!!Form::text('expediente',null,['class'=>'form-control','placeholder'=>'Número de empleado'])!!}	
								</div>
								<div class="col-md-5">
									{!!Form::text('nombre',null,['class'=>'form-control','placeholder'=>'Nombre del empleado'])!!}
								</div>
                                <div class="col-md-5">
                                Fecha de inicio
                                    {!!Form::date('fechaInicio', \Carbon\Carbon::now()->format('Y-m-d'),['class' => 'form-control'])!!}
                                </div>
                                
                                <div class="col-md-5 ">
                                Fecha final
                                    {!!Form::date('fechaFinal', \Carbon\Carbon::now()->format('Y-m-d'),['class' => 'form-control'])!!}
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
                <th>Retardos</th>
                <th>Comisión</th>
                <th>Día económico</th>
                <th>Inasistencias</th>
                <th>Incapacidad</th>
                <th>Omisión de checada</th>
                <th>Canje de tiempo extra</th>	
			</thead>
			@foreach($personal as $personals)
				<tbody>
					<tr id="{{$personals->id}}">
						<td><span {!! $personals->modulo == '1' ? "class='label label-default' title='No Activo'" : "class='label label-success' title='Activo'" !!}>{!! $personals->modulo == '1' ? 'No activo' : 'Activo' !!}</span> </td>	
						<td>{{$personals->expediente}}</td>
						<td class="text-uppercase">{{$personals->nombre}}</td>
						<td>{{reportesController::faltas($personals->id,2,$fechaInicio,$fechaFinal)}}</td>
                        <td>{{reportesController::faltas($personals->id,8,$fechaInicio,$fechaFinal)}}</td>
                        <td>{{reportesController::faltas($personals->id,7,$fechaInicio,$fechaFinal)}}</td>
                        <td>{{reportesController::faltas($personals->id,3,$fechaInicio,$fechaFinal)}}</td>
                        <td>{{reportesController::faltas($personals->id,4,$fechaInicio,$fechaFinal)}}</td>
                        <td>{{reportesController::faltas($personals->id,5,$fechaInicio,$fechaFinal)}}</td>
                        <td>{{reportesController::faltas($personals->id,6,$fechaInicio,$fechaFinal)}}</td>
					</tr>
					<tr >
						<td colspan="12" class="">
							<div class="{{$personals->id}}" id="div_maestros">
								<div class="form-group">
									<div class="row">
                                        
                                            <table class="table table-hover table-striped">
                                                <thead> 
                                                    </thead>
                                                        <th>fecha</th>
                                                        <th>hora entrada</th>
                                                        <th>entrada</th>
                                                        <th>hora salida</th>
                                                        <th>salida</th>
                                                        <th>comentario</th>
                                                    </thead>
                                                    @foreach(reportesController::falts($personals->id,$fechaInicio,$fechaFinal) as $ch)
                                                    <tbody>
                                                        <td>{{$ch->fecha}}</td>
                                                        <td>{{$ch->hora}}</td>
                                                        <td>{{$ch->checada}}</td>
                                                        <td>{{$ch->hora_salida}}</td>
                                                        <td>{{$ch->checada_salida}}</td>
                                                        <td>{{$ch->comentario}}</td>
                                                    </tbody>
                                                    @endforeach
                                                </table>
                                        
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