<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Rutas extends Model
{
    protected $table = 'rutas';

    public function cobrador()
    {
        return $this->hasOne('App\User','id','cobrador_id');
    }

    //aqui guardo los datos de la nueva ruta
    public function guardar_ruta(Request $request){
        try {
            $id=DB::table('rutas')->insertGetId(
                [
                    'ruta' =>$request->ruta,
                    'descripcion' =>$request->descripcion,
                    'cobrador_id' =>$request->cobrador_id,
                ]
            );
            return $id;
        } catch (\Throwable $th) {
            return 0;
        }
    }

    //actualizo una ruta
    public function update_ruta(Request $request,$id){
        try {
            DB::table('rutas')->where('id',$request->id_ruta)->update(
                [
                    'ruta' =>$request->ruta,
                    'descripcion' =>$request->descripcion,
                    'cobrador_id' =>$request->cobrador_id,
                ]
            );
            return $id;
        } catch (\Throwable $th) {
            DB::rollBack();
            return 0;
        }
    }

    
     //aqui elimino la ruta
     public function delete_ruta($id){
        try {
            DB::table('rutas')->where('id', '=', $id)->update(['status'=>0]);
            return $id;
        } catch (\Throwable $th) {
            return -1;
        }
    }

}
