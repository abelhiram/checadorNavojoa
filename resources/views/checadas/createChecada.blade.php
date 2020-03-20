@extends('layouts.admin')
{!!Html::style('http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css')!!}
@section('pagina')
@foreach($personal as $personal)
	Expediente: {{$personal->expediente}}
	Nombre: {{$personal->nombre}}
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
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"></div>
                	<div class="card-body">
                        <div class="form-group row" style="margin-top:15px;">
                            
                            <div class="col-md-6">
							{!!Form::open(['route'=>'checadas.store', 'method'=>'POST', 'style'=>'display:none;'])!!}
								{!!Form::text('expediente',null,['id'=>'expediente','class'=>'form-control','placeholder'=>'ingresa el expediente'])!!}
                            </div>
						</div>
						<div class="form-group row">

                            <div class="col-md-6">
							{!!Form::text('id_tblPersonal',$personal->id,['style'=>'display:none;','placeholder'=>'expediente o id'])!!}
                            </div>
                        </div>
						<div class="form-group row">
                            <label for="hora" class="col-md-4 col-form-label text-md-right">Hora de entrada</label>
                            <div class="col-md-6">
							{!! Form::time('hora',\Carbon\Carbon::now()->toTimeString(), ['class' => 'form-control']) !!} 
                            </div>
						</div>
						<div class="form-group row">
                            <label for="checada" class="col-md-4 col-form-label text-md-right">Checada</label>
                            <div class="col-md-6">
								{!!Form::select('checada',[null=>null,'0'=>'Entrada con bono','1'=>'Entrada','2'=>'Retardo','3'=>'Falta','4'=>'Incapacidad','5'=>'Omisión de checada','6'=>'Canje de tiempo extra','7'=>'Día económico','8'=>'Comisión','11'=>'Permiso por horas inicio','12'=>'Justificación: día festivo'],null, ['class' => 'form-control'])!!}
                            </div>
						</div>
						<div class="form-group row">
                            <label for="hora" class="col-md-4 col-form-label text-md-right">Hora de salida</label>
                            <div class="col-md-6">
							{!! Form::time('hora_salida',\Carbon\Carbon::now()->toTimeString(), ['class' => 'form-control']) !!} 
                            </div>
						</div>
						<div class="form-group row">
                            <label for="checada" class="col-md-4 col-form-label text-md-right">Checada de salida</label>
                            <div class="col-md-6">
								{!!Form::select('checada_salida',[null=>null,'1'=>'Salida normal','2'=>'Salida anticipada','5'=>'Omisión de salida','11'=>'Permiso por horas fin','12'=>'Justificación: día festivo'],null, ['class' => 'form-control'])!!}
                            </div>
						</div>
						<div class="form-group row">
                            <!--label for="comentario" class="col-md-4 col-form-label text-md-right">Comentario</label-->
                            <div class="col-md-6">
								{!!Form::textarea('comentario', 'checada manual',['style'=>'display:none;','size' => '10x5','class'=>'form-control','placeholder'=>'Comentario','disabled'=>'disabled'])!!}
                            </div>
						</div>
						<div class="form-group row">
                            <label for="fecha" class="col-md-4 col-form-label text-md-right">Fecha</label>
                            <div class="col-md-6">
								{!!Form::date('fecha', \Carbon\Carbon::now(),['class'=>'form-control'])!!}
                            </div>
						</div>
						<div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
							<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
								Registrar checada
							</button>
								{!!link_to_route('checadas.show', $title = 'Volver', $parameters = $personal->id, $attributes = ['class'=>'btn btn-warning']);!!}
								
								<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered" role="document">
										<div class="modal-content">
										<div class="modal-header">
											<h5 class="modal-title" id="exampleModalLongTitle">¿Estas seguro que deseas modificar esta checada?</h5>
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
								{!!Form::close()!!}
                            </div>
                        </div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
	