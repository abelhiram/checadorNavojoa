{!!Html::style('https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css')!!}
<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Login chequeos</title>
        <link rel="icon" href="{!! asset('img/Icono256_UES.png') !!}"/>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        
        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 54px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
                width: 600px;
            }

            .m-b-md {
                margin-bottom: 15px;
            }
        </style>
    </head>

    <body onload="">
        <div class="flex-center position-ref full-height">

            <div class="content">
                <div class="title m-b-md">
                    Login
                    <hr>
                	{!!Form::open(['route'=>'login', 'method'=>'POST', 'class'=>''])!!}
                    {{ csrf_field() }}
                    <div class="form-group">
					{!!Form::text('email',old('email'),['class'=>'form-control','placeholder'=>'email o id'])!!}
                    <h6>{{ $errors->first('email',':message') }}</h6>
                    </div>
                    <div class="form-group">
                    {!!Form::text('password',null,['class'=>'form-control','type'=>'password','placeholder'=>'contraseña'])!!}

                    <h6>{{ $errors->first('password',':message') }}</h6>
                    </div>

					<!--\Carbon\Carbon::now()->toTimeString()-->
					<hr>
					{!!Form::submit('Log-in',['class'=>'btn btn-primary btn-block'])!!}
					
					{!!Form::close()!!}
                    
                    

	                <div class="links">
	                    {!!link_to_route('personal')!!}
	                </div>
                </div>
            </div>    
        </div>
    </body>
</html>
