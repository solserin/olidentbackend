<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class GruposVendedores extends Model
{
    protected $table = 'grupos_vendedores';

    //un grupo de vendedores tienen muchos usuarios asociados
    public function vendedores(){
        return $this->hasMany('App\User','grupos_vendedores_id','id');
    }


     //aqui guardo los datos del nuevo grupo
     public function guardar_grupo(Request $request){
        try {
            $id=DB::table('grupos_vendedores')->insertGetId(
                [
                    'grupo' =>$request->grupo
                ]
            );
            return $id;
        } catch (\Throwable $th) {
            return 0;
        }
    }


    public function update_grupo(Request $request,$id){
        try {
            DB::table('grupos_vendedores')->where('id',$id)->update(['grupo' => $request->grupo]);
            return $id;
        } catch (\Throwable $th) {
            return 0;
        }
    }


     //aqui elimino el grupo
     public function delete_grupo($id){
        try {
            DB::table('grupos_vendedores')->where('id', '=', $id)->update(['status'=>0]);
            return $id;
        } catch (\Throwable $th) {
            return -1;
        }
    }

    

}
