@extends('layouts.admin')
{!!Html::style('http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css')!!}
@section('contenido')	

@section('pagina')
@foreach($personal as $personal)
	Expediente: {{$personal->expediente}}
	Nombre: {{$personal->nombre}}
@endforeach
@stop
@if(Session::has('message'))
	<?php $message=Session::get('message') ?>
	<div class="alert alert-success alert-dismissible" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		{{Session::get('message')}}
	</div>
@endif
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"></div>
                	<div class="card-body">
                        <div class="form-group row" style="margin-top:15px;">
                            <div class="col-md-6">
							{!!Form::model($mdlHorarios,['route'=>['horarios.update',$mdlHorarios->id],'method'=>'PUT', 'class'=>''])!!}
                            </div>
						</div>
						<div class="form-group row">
                            <div class="col-md-6">
							{!!Form::text('id_tblPersonal',$personal->id,['style'=>'display:none;','placeholder'=>'expediente o id'])!!}
                            </div>
						</div>
						<div class="form-group row">
                            <label for="dia" class="col-md-4 col-form-label text-md-right">Día</label>
                            <div class="col-md-6">
							{!!Form::select('dia',['1'=>'Lunes','2'=>'Martes','3'=>'Miercoles','4'=>'Jueves','5'=>'viernes','6'=>'Sábado','7'=>'Domingo'],null, ['class' => 'form-control'])!!}
                            </div>
						</div>
						<div class="form-group row">
                            <label for="hora_entrada" class="col-md-4 col-form-label text-md-right">Hora de entrada</label>
                            <div class="col-md-6">
							{!! Form::time('hora_entrada',null, ['class' => 'form-control']) !!} 
                            </div>
						</div>
						<div class="form-group row">
                            <label for="hora_salida" class="col-md-4 col-form-label text-md-right">Hora de salida</label>
                            <div class="col-md-6">
							{!! Form::time('hora_salida',null, ['class' => 'form-control']) !!}
                            </div>
						</div>
						<div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
							<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
								Actualizar
								</button>
<!-- Modal -->
								<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
								<div class="modal-dialog modal-dialog-centered" role="document">
									<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="exampleModalLongTitle">¿Estas seguro que deseas modificar este horario?</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
									<h5 class="modal-title" id="exampleModalLongTitle">Ingresa la clave se seguridad</h5>
									{!!Form::password('seguridad',null,['class' => 'form-control','placeholder'=>'Clave de seguridad','type' => 'password'])!!}
									
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
										{!!Form::submit('Aceptar',['class'=>'btn btn-primary'])!!}
									</div>
									</div>
								</div>
								</div>
							{!!link_to_route('horarios.show', $title = 'Volver', $parameters = $personal->id, $attributes = ['class'=>'btn btn-warning']);!!}
							{!!Form::close()!!}	
                            </div>
						</div>
						{!!Form::open(['route'=>['horarios.destroy',$mdlHorarios->id],'method'=>'DELETE'])!!}
						<button type="button" class="btn btn-danger pull-right" style="margin-top:-49px;margin-right:130px;" data-toggle="modal" data-target="#exampleModal">
								Eliminar
								</button>
<!-- Modal -->
								<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
								<div class="modal-dialog modal-dialog-centered" role="document">
									<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="exampleModalLongTitle">¿Estas seguro que deseas modificar este usuario?</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
									<h5 class="modal-title" id="exampleModalLongTitle">Ingresa la clave se seguridad</h5>
									{!!Form::password('seguridad',null,['class' => 'form-control','placeholder'=>'Clave de seguridad','type' => 'password'])!!}
									
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
										{!!Form::submit('Aceptar',['class'=>'btn btn-danger'])!!}
									</div>
									</div>
								</div>
								</div>
						
						{!!Form::close()!!}
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection