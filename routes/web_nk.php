<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//Route::resource('reportes','reportesController');
Route::resource('personal','personalController');
Route::resource('checadas','checadasController');
Route::resource('horarios','horariosController');

Route::get('/reportes/','reportesController@index')->name('reportes');
Route::get('/personal/','personalController@index')->name('personal');
Route::get('/checadas/','checadasController@index')->name('checadas');
//Route::get('/checadas/f1/{fechaInicio}/f2/{fechaFinal}','checadasController@index')->name('checadas');
Route::get('/horario/{id}','horariosController@crear')->name('horarios.crear');
Route::get('/checadas/crear/{id}','checadasController@crear')->name('checadas.crear');
Route::get('/crearchecada/{id_tblPersonal}/hora/{hora}','checadasController@crearchecada');
Route::get('/crearchecada2/{id_tblPersonal}/hora/{hora}','checadasController2@crearchecada');
Route::get('/horatest/','checadasController@index')->name('horatest');
Route::get('/faltas/{id_tblPersonal}/fecha/{fecha}','checadasController@faltas');
//Route::match(['get', 'post'], 'horarios', 'horariosController@index');

