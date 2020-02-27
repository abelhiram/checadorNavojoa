{!!Html::style('https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css')!!}
<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>UES Virtual - Checador</title>
        <link rel="icon" href="{!! asset('img/Icono256_UES.png') !!}"/>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <!-- Scripts -->
        <script src="{{ asset('/js/inicio.js') }}"></script>
        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('/css/inicio.css') }}">
    </head>

    <body onload="startTime()">

        <div class="flex-center position-ref full-height">
            <div class="top-right links">              
                <a href="{{ url('login') }}">Login</a>
            </div>

            <div class="content">

                <div class="mb-5">
                    <img src="img/logo_virtual.png " alt="UES Virtual" class="img-fluid">
                </div>

                <div class="title m-b-md">
                	{!!Form::open(['route'=>'checkin.store', 'method'=>'POST', 'class'=>''])!!}
                        <label id="hora"></label>

                        
					{!!Form::close()!!}
					
	                <div class="links">
	                	@if(Session::has('message'))
				          <?php $message=Session::get('message') ?>
				          <div role="alert">
				          	<h5>{{Session::get('message')}}</h5>
				          
				          </div>
				        @endif 
                    </div>
                    
                </div>

            </div>

        </div>
    </body>
</html>


	