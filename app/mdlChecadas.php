<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class mdlChecadas extends Model
{
    protected $table = 'tblChecadas'; 
    protected $fillable = ['id_tblPersonal','hora','hora_salida','checada','checada_salida','entradaHoras','salidaHoras','comentario','fecha','turno'];  

}
