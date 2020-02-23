<?php use App\Http\Controllers\reportesController ?>
@extends('layouts.admin')
{!!Html::style('http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css')!!}
@section('pagina')
    Reportes generales
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
        <div class="row">
            <div class="col-sm-6 mt-5">
                <div class="card" >
                    <div class="card-body-sm-6" >
                        <h2 class="card-title">Reporte de empleados</h5>
                        <h4 class="card-text">Generar reporte(en formato excel) de empleados.</h3>
                        <a href="" class="card-link btn btn-primary">Generar reporte</a>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 mt-5">
                <div class="card">
                    <div class="card-body-sm-6">
                        <h2 class="card-title">Reporte de administradores</h5>
                        <h4 class="card-text">Generar reporte(en formato excel) de administradores de sistema.</h3>
                        <a href="" class="card-link btn btn-primary">Generar reporte</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection