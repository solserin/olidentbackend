<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    const USUARIO_ACTIVO="1";
    const USUARIO_NO_ACTIVO="0";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    

    public function estaActivo(){
        return $this->status==User::USUARIO_ACTIVO;
    }



    //relaciones con otras tablas

    //un usuario puede tener solo un  rol (roles)
    public function rol(){
        return $this->belongsTo('App\Roles','roles_id','id');
    }

}