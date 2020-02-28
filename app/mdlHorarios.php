<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class mdlHorarios extends Model
{
    protected $table = 'tblhorarios';   
    protected $fillable = ['id_tblPersonal','dia','hora_entrada','hora_salida'];
}
