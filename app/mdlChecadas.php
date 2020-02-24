<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class mdlChecadas extends Model
{
    protected $table = 'tblchecadas'; 
    protected $fillable = ['id_tblPersonal','hora','hora_salida','checada','checada_salida','comentario','fecha','turno'];  

}
