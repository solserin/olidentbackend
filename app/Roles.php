<?php

namespace App;

use Hamcrest\Type\IsNumeric;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;


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

    //aqui guardo los datos y permisos del nuevo rol
    public function guardar_rol(Request $request){
        try {
            DB::beginTransaction();
            $rol_id=DB::table('roles')->insertGetId(['rol' =>$request->rol]);
            $id_modulo=0;
            $id_permiso=0;
            $valores="";
            foreach($request->itemsPermisos as $item){
                $valores=explode(",", $item);
                $id_modulo=$valores[0];
                $id_permiso=$valores[1];
                //sacando el valor del modulo
                DB::table('roles_permisos')->insert(
                    [
                         'modulos_id' => (int)($id_modulo),
                         'permisos_id' =>(int)($id_permiso),
                         'roles_id' => $rol_id
                    ]
                );
            }
            DB::commit();
            return $rol_id;
        } catch (\Throwable $th) {
            DB::rollBack();
            return 0;
        }
    }

}

