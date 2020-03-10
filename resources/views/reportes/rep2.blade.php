<?php use App\Http\Controllers\reportesController;
if(!isset($_GET['fechaInicio'])){
	$fechaInicio = date("Y-m-d");
} else{
	$fechaInicio = $_GET['fechaInicio'];
}

if(!isset($_GET['fechaFinal'])){
	$fechaFinal = date("Y-m-d");
} else{
	$fechaFinal = $_GET['fechaFinal'];
}
?>
<script>
var tableToExcel = (function() {
  var uri = 'application/vnd.openxmlformats-officedocument.spreadsheetml.template;base64,'
    , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="https://www.w3.org/TR/html401/"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
    , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
    , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
  return function(table, name) {
    if (!table.nodeType) table = document.getElementById(table)
    var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
    window.location.href = uri + base64(format(template, ctx))
  }
})()

</script>
@extends('layouts.admin')
{!!Html::style('http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css')!!}

@section('pagina')
Reportes
@stop

@section('contenido')

	@if(Session::has('message'))
		<?php $message=Session::get('message') ?>
		<div class="alert alert-success alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			{{Session::get('message')}}
		</div>
	@endif
	
    
	<div class="panel-group">
		<div class="panel panel-danger">
			<div class="panel-heading"><h4>Buscar</h4></div>
			<div class="panel-content">
				<div class="row top30 bot30">
					{!!Form::open(['route'=>'reportes', 'method'=>'GET', 'id'=>'formReportes', 'class'=>'form-block'])!!}

						<div class="col-md-12 col-md-offset-1">
							<div class="row">
								<div class="col-md-5 ">
									{!!Form::text('expediente',null,['class'=>'form-control','placeholder'=>'Número de empleado'])!!}	
								</div>
								<div class="col-md-5">
									{!!Form::text('nombre',null,['class'=>'form-control','placeholder'=>'Nombre del empleado'])!!}
								</div>
                                <div class="col-md-5">
                                Fecha de inicio
                                    {!!Form::date('fechaInicio', \Carbon\Carbon::now()->format('Y-m-d'),['class' => 'form-control'])!!}
                                </div>
                                
                                <div class="col-md-5 ">
                                Fecha final
                                    {!!Form::date('fechaFinal', \Carbon\Carbon::now()->format('Y-m-d'),['class' => 'form-control'])!!}
                                </div>
                            </div>
						</div>
						<div class="row">
							<div class="col-md-4  col-md-offset-4 top15">
								{!!Form::submit('Buscar',['class'=>'btn btn-primary btn-block','style'=>'margin-top:10px'])!!}
							</div>
						</div>
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-4  col-md-offset-4 top15">
        <input type="button" class="btn btn-primary btn-block" onclick="tableToExcel('testTable', 'Reportes generales')" value="Reportes generales excel">
    </div>
	<div class="tableInfo top30">
		<table class="table table-hover table-striped" id="" summary="Code page support in different versions of MS Windows." rules="groups" >
			<thead>
				<th><span>Activo</span></th>
				<th>No. Emp.</th>
                <th>Nombre</th>
                <th>Retardos</th>
                <th>Comisión</th>
                <th>Día económico</th>
                <th>Inasistencias</th>
                <th>Salida anticipada</th>
                <th>Incapacidad</th>
                <th>Omisión de entrada</th>
                <th>Omisión de salida</th>
                <th>Canje de tiempo extra</th>	
                <th>Bono</th>
			</thead>
			@foreach($personal as $personals)
				<tbody>
					<tr id="{{$personals->id}}">
						<td><span {!! $personals->modulo == '1' ? "class='label label-default' title='No Activo'" : "class='label label-success' title='Activo'" !!}>{!! $personals->modulo == '1' ? 'No activo' : 'Activo' !!}</span> </td>	
						<td>{{$personals->expediente}}</td>
						<td class="text-uppercase">{{$personals->nombre}}</td>
						<td>{{reportesController::faltas($personals->id,2,$fechaInicio,$fechaFinal)}}</td>
                        <td>{{reportesController::faltas($personals->id,8,$fechaInicio,$fechaFinal)}}</td>
                        <td>{{reportesController::faltas($personals->id,7,$fechaInicio,$fechaFinal)}}</td>
                        <td>{{reportesController::faltas($personals->id,3,$fechaInicio,$fechaFinal)}}</td>
                        <td>{{reportesController::faltas2($personals->id,2,$fechaInicio,$fechaFinal)}}</td>
                        <td>{{reportesController::faltas($personals->id,4,$fechaInicio,$fechaFinal)}}</td>
                        <td>{{reportesController::faltas($personals->id,5,$fechaInicio,$fechaFinal)}}</td>
                        <td>{{reportesController::faltas2($personals->id,5,$fechaInicio,$fechaFinal)}}</td>
                        <td>{{reportesController::faltas($personals->id,6,$fechaInicio,$fechaFinal)}}</td>
                        @if(reportesController::bono($personals->id,$fechaInicio,$fechaFinal)==0)
                            <td><label class="glyphicon glyphicon-ok " style="color:green"></label></td>
                        @else
                            <td><label class="glyphicon glyphicon-remove"  style="color:red"></label></td>
                        @endif
					</tr>
					<tr >
						<td colspan="12" class="">
							<div class="{{$personals->id}}" id="div_maestros">
								<div class="form-group">
									<div class="row">
                                        
                                        <table class="table table-hover table-striped">
                                        	<thead> 
                                                <th>Fecha</th>
                                                <th>Hora entrada</th>
                                                <th>Entrada</th>
                                                <th>Hora salida</th>
                                                <th>Salida</th>
                                                <th>Permiso por horas inicio</th>
                                                <th>Permiso por horas fin</th>
                                                <th>Comentario</th>
                                            </thead>
                                            @foreach(reportesController::falts($personals->id,$fechaInicio,$fechaFinal) as $ch)
                                            <tbody>
                                                <td>{{$ch->fecha}}</td>
                                                <td>{{$ch->hora}}</td>
                                                @if($ch->checada==0)
                                                <td>Con bono</td>
                                                @endif
                                                @if($ch->checada==1)
                                                <td>Normal</td>
                                                @endif
                                                @if($ch->checada==2)
                                                <td>Retardo</td>
                                                @endif
                                                @if($ch->checada==3)
                                                <td>Falta por tiempo</td>
                                                @endif
                                                @if($ch->checada==4)
                                                <td>Incapacidad</td>
                                                @endif
                                                @if($ch->checada==5)
                                                <td>Omision</td>
                                                @endif
                                                @if($ch->checada==6)
                                                <td>Canje tiempo extra</td>
                                                @endif
                                                @if($ch->checada==7)
                                                <td>Día económico</td>
                                                @endif
                                                @if($ch->checada==8)
                                                <td>Comision</td>
                                                @endif
                                                @if($ch->checada==11)
                                                <td>Permiso por horas inicio</td>
                                                @endif

                                                <td>{{$ch->hora_salida}}</td>

                                                @if($ch->checada_salida==1)
                                                <td>Salida</td>
                                                @endif
                                                @if($ch->checada_salida==2)
                                                <td>Salida anticipada</td>
                                                @endif
                                                @if($ch->checada_salida==5)
                                                <td>Omision</td>
                                                @endif
                                                @if($ch->checada_salida==11)
                                                <td>Permiso por horas fin</td>
                                                @endif
                                                <td>{{$ch->entradaHoras}}</td>
                                                <td>{{$ch->salidaHoras}}</td>
                                                <td>{{$ch->comentario}}</td>
                                            </tbody>
                                            @endforeach
                                        </table>
									</div>
								</div>
							</div>	
						</td>
					</tr>		
				</tbody>
			@endforeach	
		</table>
	</div>
    
		<table class="invisible" style="height:0%;position:absolute;" id="testTable">
			<thead>
				<th><span>Activo</span></th>
				<th>No. Emp.</th>
                <th>Nombre</th>
                <th>Retardos</th>
                <th>Comisión</th>
                <th>Día económico</th>
                <th>Inasistencias</th>
                <th>Incapacidad</th>
                <th>Omisión de checada</th>
                <th>Canje de tiempo extra</th>	
                <th>Bono</th>
			</thead>
			@foreach($personal as $personals)
				<tbody>
					<tr id="{{$personals->id}}">
						<td><span {!! $personals->modulo == '1' ? "class='label label-default' title='No Activo'" : "class='label label-success' title='Activo'" !!}>{!! $personals->modulo == '1' ? 'No activo' : 'Activo' !!}</span> </td>	
						<td>{{$personals->expediente}}</td>
						<td class="text-uppercase">{{$personals->nombre}}</td>
						<td>{{reportesController::faltas($personals->id,2,$fechaInicio,$fechaFinal)}}</td>
                        <td>{{reportesController::faltas($personals->id,8,$fechaInicio,$fechaFinal)}}</td>
                        <td>{{reportesController::faltas($personals->id,7,$fechaInicio,$fechaFinal)}}</td>
                        <td>{{reportesController::faltas($personals->id,3,$fechaInicio,$fechaFinal)}}</td>
                        <td>{{reportesController::faltas($personals->id,4,$fechaInicio,$fechaFinal)}}</td>
                        <td>{{reportesController::faltas($personals->id,5,$fechaInicio,$fechaFinal)}}</td>
                        <td>{{reportesController::faltas($personals->id,6,$fechaInicio,$fechaFinal)}}</td>
                        @if(reportesController::bono($personals->id,$fechaInicio,$fechaFinal)==0)
                            <td>SI</td>
                        @else
                            <td>NO</td>
                        @endif
					</tr>
				</tbody>
			@endforeach	
		</table>
	

	<script type="text/javascript" src="{{ URL::asset('/js/reportes.js') }}"></script>
	<link rel="stylesheet" href="{{ URL::asset('/css/reportes.css') }}">

	{!!$personal->render()!!}
@endsection
