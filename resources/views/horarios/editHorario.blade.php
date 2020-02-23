@extends('layouts.admin')
{!!Html::style('http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css')!!}
@section('contenido')	

@section('pagina')
@foreach($personal as $personal)
	Expediente: {{$personal->expediente}}
	Nombre: {{$personal->nombre}}
@endforeach
@stop

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
							{!!Form::submit('Actualizar horario',['class'=>'btn btn-primary'])!!}
							{!!link_to_route('horarios.show', $title = 'Cancelar', $parameters = $personal->id, $attributes = ['class'=>'btn btn-warning']);!!}
							{!!Form::close()!!}	
                            </div>
						</div>
						{!!Form::open(['route'=>['horarios.destroy',$mdlHorarios->id],'method'=>'DELETE'])!!}

						{!!Form::submit('Eliminar',['class'=>'btn btn-danger pull-right','style'=>'margin-top:-49px;margin-right:130px;'])!!}
						{!!Form::close()!!}
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection