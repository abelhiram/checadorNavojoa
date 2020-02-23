<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class mdlHorarios extends Model
{
    protected $table = 'tblHorarios';   
    protected $fillable = ['id_tblPersonal','dia','hora_entrada','hora_salida'];
}
