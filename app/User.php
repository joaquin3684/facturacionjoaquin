<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use SoftDeletes;
    protected $table = 'usuarios';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre', 'email', 'password', 'user', 'id_empresa', 'token', 'refresh_token', 'expires_in'
    ];


    public function perfiles()
    {
        return $this->belongsToMany('App\Perfil', 'usuario_perfil', 'id_usuario', 'id_perfil');
    }

    public function obrasSociales()
    {
        return $this->belongsToMany('App\ObraSocial', 'usuario_obra_social', 'id_usuario', 'id_obra_social');
    }

    public function subordinados()
    {
        return $this->hasMany('App\User', 'id_jefe','id');
    }

    public function jefe()
    {
        return $this->belongsTo('App\User', 'id_jefe', 'id');
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];
}
