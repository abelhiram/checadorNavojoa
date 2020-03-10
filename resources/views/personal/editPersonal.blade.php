						@extends('layouts.admin')
						{!!Html::style('http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css')!!}
						@section('pagina')
                            Modificar personal
                        @stop
						@section('contenido')	
						@if(Session::has('message'))
							<?php $message=Session::get('message') ?>
							<div class="alert alert-success alert-dismissible" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								{{Session::get('message')}}
							</div>
						@endif
						{!!Form::model($mdlPersonal,['route'=>['personal.update',$mdlPersonal->id],'method'=>'PUT','enctype'=>'multipart/form-data', 'class'=>''])!!}
						<div align="center">
							<img style="width:100px;height:100px;" src="{{ asset($mdlPersonal->photo_route) }}">
						</div>
							<br>
						@include('personal.forms.personal')

						<div class="form-group row mb-0">
						<div class="col-md-4 col-form-label text-md-right"></div>
                            <div class="col-md-6 offset-md-4">
							<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
								Actualizar
								</button>
								{!!link_to_route('personal', $title = 'Cancelar', $parameters = null, $attributes = ['class'=>'btn btn-warning']);!!}
								

								<!-- Modal -->
								<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
										{!!Form::submit('Aceptar',['class'=>'btn btn-primary'])!!}
									</div>
									</div>
								</div>
								</div>
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

