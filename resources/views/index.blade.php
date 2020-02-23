@extends('layouts.admin')
{!!Html::style('http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css')!!}
@section('pagina')
Personal
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
				<div class="row">
					
					{!!Form::open(['class'=>'form-block', 'route'=>'personal', 'method'=>'GET'])!!}
						
						<div class="col-md-6 top15">
							{!!Form::text('expediente',null,['class'=>'form-control','placeholder'=>'Número de empleado'])!!}
						</div>
					
						<div class="col-md-6 top15">
							{!!Form::text('nombre', null,['class'=>'form-control','placeholder'=>'Nombre del empleado'])!!}
						</div>

						<div class="row-12">
							<div class="col-md-4  col-md-offset-4 top15">
								{!!Form::submit('Buscar',['class'=>'btn btn-primary btn-block bot15','style'=>'margin-top:10px'])!!}
							</div>
						</div>
						
					{!!Form::close()!!}
				</div>
			</div>
		</div>
	</div>
	
	<div class="row bot15">
		<div class="col-md-12">
			<div class="col-md-2 pull-right">
				{{ HTML::linkRoute('personal.create', 'Añadir nuevo', array(), array('class'=>'btn btn-success top15')) }}
			</div>
		</div>
	</div>
	
	<div id="personalTable">
		<table class="table table-hover table-striped">
		<thead>
			<th>No. Emp.</th>
			<th>Nombre</th>
			<th>Email</th>
			<th>Nombramiento</th>
			<th>Jornada</th>
			<th>Activo</th>
		</thead>
		@foreach($personal as $personals)
		<tbody>
			<td>{{$personals->expediente}}</td>
			<td class="text-uppercase">{{$personals->nombre}}</td>
			<td>{{$personals->email}}</td>
			<td>{{$personals->nombramiento}}</td>
			<td>{{$personals->jornada}}</td>
			@if($personals->modulo!=1)
				<td><label class="glyphicon glyphicon-ok " style="color:green"></label></td>
			@else
				<td><label class="glyphicon glyphicon-remove"></label></td>
			@endif
			<td>
			<div class="btn-group">
				<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<span class="glyphicon glyphicon-option-horizontal"></span> 
				</button>
				<ul class="dropdown-menu" style="left: -68px; ">
					<li>
						{!!link_to_route('personal.edit', $title = 'Editar', $parameters = $personals->id, $attributes = ['class'=>'glyphicon glyphicon-pencil']);!!}
					</li>
					<li>
						{!!link_to_route('horarios.show', $title = 'Horario', $parameters = $personals->id, $attributes = ['class'=>'glyphicon glyphicon-calendar']);!!}
					</li>
					<li>
						{!!link_to_route('checadas.show', $title = 'Checadas', $parameters = $personals->id, $attributes = ['class'=>'glyphicon glyphicon-ok']);!!}	
					</li>
					<li role="separator" class="divider"></li>
					<li>
						{!!link_to_route('reporte.quincenal', $title = 'Quincenal', $parameters = $personals->id, $attributes = ['class'=>'glyphicon glyphicon-download-alt']);!!}	
					</li>
					<li>
						{!!link_to_route('reporte.semanal', $title = 'Semanal', $parameters = $personals->id, $attributes = ['class'=>'glyphicon glyphicon-download-alt']);!!}	
					</li>
				</ul>
			</div>
		</tbody>
		@endforeach	
		</table>
	</div>
	<link rel="stylesheet" href="{{ asset('/css/personal.css') }}">		
	{!!$personal->render()!!}
@endsection