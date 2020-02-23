<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>UES Virtual - Iniciar sesión</title>
    <link rel="icon" href="{!! asset('img/Icono256_UES.png') !!}"/>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <!-- Fonts -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('/css/login.css') }}">
</head>
<body>

    <div class="container flex-center position-ref full-height">
        <div class="row">
            <div class="col-md-12">
                <div class="content">
                    <div class="title m-b-md">
                        <div class="mb-5">
                            <img src="img/logo_virtual.png " alt="UES Virtual" class="img-fluid">
                        </div>
                        <p style="font-size:35px;" class="font-weight-bold mb-4 justify-content-center">Iniciar sesión</p>
                        <hr>
                        <form method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
                            @csrf
                                
                            <div class="form-group row-md-12 form-inline">
                                <div class="col-md-4 top15">
                                    <label for="email" class="label">{{ __('Email') }}</label>
                                </div>

                                <div class="col-md-8 top15">
                                    <input id="email" type="email" class="input form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row-md-12 form-inline">
                                <div class="col-md-4 top15 bot15">
                                    <label for="password" class="label">{{ __('Contraseña') }}</label>
                                </div>
                                <div class="col-md-8 top15 bot15">
                                    <input id="password" type="password" class="input form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <hr/>

                            <div class="form-group row mb-0">
                                <div class="col-md-4 offset-md-4">
                                    <button type="submit" class="btn btn-primary btn-block top15">
                                        {{ __('Login') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>    
        </div>    
    </div>

</body>
</html>
