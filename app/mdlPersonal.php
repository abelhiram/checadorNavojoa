<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class mdlPersonal extends Model
{
	protected $table = 'tblPersonal';   
	protected $fillable = ['expediente','nombre','email', 'nombramiento','jornada','huella','modulo'];
	
	public function scopeNombre($query, $nombre){
		if($nombre){
			return $query->where('nombre', 'like', "%$nombre%");
		}
	}
	public function scopeExpediente($query, $expediente){
		if($expediente){
			return $query->where('expediente', 'like', "$expediente%");
		}
	}

	public function getPhotoRouteAttribute()
	{
        if($this->foto!='no')
            return 'img/usuarios/'.$this->expediente.'.'.$this->foto;

        	return 'img/usuarios/default.png'; 
    }

}
	