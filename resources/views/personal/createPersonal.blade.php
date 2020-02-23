                        @extends('layouts.admin')
                        {!!Html::style('http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css')!!}
                        @section('pagina')
                            Registrar personal
                        @stop
                        @section('contenido')	
                        {!!Form::open(['route'=>'personal.store', 'method'=>'POST', 'enctype'=>'multipart/form-data', 'class'=>''])!!}
                        @include('personal.forms.personal')
                        <div class="form-group row mb-0">
                        <div class="col-md-4 col-form-label text-md-right"></div>
                            <div class="col-md-6 offset-md-4">
                            {!!Form::submit('Registrar',['class'=>'btn btn-primary'])!!}
                            {!!link_to_route('personal', $title = 'Cancelar', $parameters = null, $attributes = ['class'=>'btn btn-warning']);!!}
                            {!!Form::close()!!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection