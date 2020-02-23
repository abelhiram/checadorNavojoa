						@extends('layouts.admin')
						{!!Html::style('http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css')!!}
						@section('pagina')
                            Modificar personal
                        @stop
						@section('contenido')	
						{!!Form::model($mdlPersonal,['route'=>['personal.update',$mdlPersonal->id],'method'=>'PUT','enctype'=>'multipart/form-data', 'class'=>''])!!}
						<div align="center">
							<img style="width:100px;height:100px;" src="{{ asset($mdlPersonal->photo_route) }}">
						</div>
							<br>
						@include('personal.forms.personal')

						<div class="form-group row mb-0">
						<div class="col-md-4 col-form-label text-md-right"></div>
                            <div class="col-md-6 offset-md-4">
								{!!Form::submit('Actualizar',['class'=>'btn btn-primary'])!!}
								{!!link_to_route('personal', $title = 'Cancelar', $parameters = null, $attributes = ['class'=>'btn btn-warning']);!!}
								{!!Form::close()!!}
                            </div>
                        </div>
						<!--
						{!!Form::open(['route'=>['personal.destroy',$mdlPersonal->id],'method'=>'DELETE'])!!}
						{!!Form::submit('Eliminar',['class'=>'btn btn-danger pull-right','style'=>'margin-top:-49px;margin-right:130px;'])!!}
						{!!Form::close()!!}
						-->
					</div>
				</div>
			</div>
		</div>
	</div>

@endsection

