<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"></div>
                	<div class="card-body">
                        <div class="form-group row" style="margin-top:15px;">
                            <label for="expediente" class="col-md-4 col-form-label text-md-right">Expediente</label>
                            <div class="col-md-6">
								{!!Form::text('expediente',null,['id'=>'expediente','class'=>'form-control','placeholder'=>'ingresa el expediente','required'])!!}
                                @if ($errors->has('expediente'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('expediente') }}</strong>
                                    </span>
                                @endif
                            </div>
						</div>
						<div class="form-group row">
                            <label for="nombre" class="col-md-4 col-form-label text-md-right">Nombre</label>
                            <div class="col-md-6">
							    {!!Form::text('nombre',null,['class'=>'form-control','placeholder'=>'ingresa el nombre del empleado','required'])!!}
                                @if ($errors->has('nombre'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('nombre') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
						<div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>
                            <div class="col-md-6">
							    {!!Form::text('email',null,['class'=>'form-control','placeholder'=>'ingresa el e-mail','required','email'])!!}
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
						</div>
						<div class="form-group row">
                            <label for="nombramiento" class="col-md-4 col-form-label text-md-right">Nombramiento</label>
                            <div class="col-md-6">
							{!!Form::select('nombramiento',['determinado' => 'determinado','indeterminado'=>'indeterminado'],null, ['class' => 'form-control'])!!}
                            </div>
						</div>
						<div class="form-group row">
                            <label for="jornada" class="col-md-4 col-form-label text-md-right">Jornada</label>
                            <div class="col-md-6">
							{!!Form::select('jornada',['horas'=>'horas','tiempo completo'=>'tiempo completo','medio tiempo'=>'medio tiempo','confianza'=>'confianza'],null, ['class' => 'form-control'])!!}
                            </div>
						</div>
						<div class="form-group row">
                            <label for="foto" class="col-md-4 col-form-label text-md-right">Foto</label>
                            <div class="col-md-6">
							{!!Form::file('foto',['class'=>'form-control'])!!}
                            </div>
						</div>
                        <div class="form-group row">
                            <label for="foto" class="col-md-4 col-form-label text-md-right">Activo</label>
                            <div class="col-md-6">
                            {!!Form::select('modulo',['0'=>'Activo','1'=>'Inactivo'],null, ['class' => 'form-control'])!!}
                            </div>
						</div>
						<div class="" style="display:none;">
							{!!Form::text('huella',null,['class'=>'huella'])!!}	
                        </div>
                	</div>
            </div>
        </div>
    </div>
</div>
