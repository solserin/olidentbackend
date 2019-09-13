<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;


class Roles extends Model
{
    protected $table = 'roles';



    //un rol tiene muchos usuarios
    public function usuarios(){
        return $this->hasMany('App\User','roles_id','id');
    }


    //regresa todos los datos para crear la interfaz de un usuario
    public function roles_modulos_permisos($id_user=''){
        $data=DB::table('users')
        ->join('roles', 'users.roles_id', '=', 'roles.id')
        ->join('roles_permisos', 'roles_permisos.roles_id', '=', 'roles.id')
        ->join('modulos', 'roles_permisos.modulos_id', '=', 'modulos.id')
        ->join('grupos', 'grupos.id', '=', 'modulos.grupos_id')
        ->join('permisos', 'roles_permisos.permisos_id', '=', 'permisos.id')
        ->select(
            'users.id',
            'users.email',
            'grupos.grupo', 
            'roles.rol',
            'modulos.id as modulo_id',
            'grupos.id as grupo_id',
            'modulos.modulo',
            'modulos.name',
            'modulos.url',
            'modulos.icon',
            'permisos.permiso'
        )
        ->where('users.id','=',$id_user)
        ->get();
        return $data;
    }

     //regresa todos los datos por filtro de token
     public function get_user_by_token($token=''){
        $data=DB::table('users')
        ->join('oauth_access_tokens', 'users.id', '=', 'oauth_access_tokens.user_id')
        ->select(
            'users.id',
            'users.email'
        )
        ->where(
            [
                'oauth_access_tokens.id'=>$token,
                'users.status'=>1
            ]
        )
        ->get();
        return $data;
    }





}

