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

    //aqui obtengo los ids de los permisos que le corresponden a cada rol para modificar un rol
    public function get_permisos_de_rol($id){
        return DB::table('roles_permisos')->where('roles_id',$id)->get();
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
            'grupos.icon as grupo_icon',
            'modulos.modulo',
            'modulos.name',
            'modulos.url',
            'modulos.icon',
            'permisos.id as permiso_id',
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


    //actualizo un rol con esta funcion
     public function update_rol(Request $request,$id){
        try {
            DB::beginTransaction();
            DB::table('roles_permisos')->where('roles_id', '=', $request->id_rol)->delete();
            DB::table('roles')->where('id',$request->id_rol)->update(['rol' => $request->rol]);
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
                         'roles_id' => $id
                    ]
                );
            }
            DB::commit();
            return $id;
        } catch (\Throwable $th) {
            DB::rollBack();
            return 0;
        }
    }


     //aqui elimino el rol
     public function delete_rol($id){
        try {
            DB::table('roles')->where('id', '=', $id)->update(['status'=>0]);
            return $id;
        } catch (\Throwable $th) {
            return -1;
        }
    }

}