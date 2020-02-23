<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['expediente','name','email','nombramiento','jornada','huella','password',];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function scopeNombre($query, $nombre){
        if($nombre){
            return $query->where('name', 'like', "%$nombre%");
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
