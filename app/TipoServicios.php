<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class TipoServicios extends Model
{
    protected $table = 'tipo_servicios';


    
    //relacion de los servicios que tiene esta categoria de tipos de servicios
    public function servicios()
    {
        return $this->hasMany('App\Servicios','tipo_id','id');
    }


    //aqui guardo los datos del nuevo tipo de servicio
    public function guardar_tipo(Request $request){
        try {
            $last_id=DB::table('tipo_servicios')->insertGetId(
                    [
                         'tipo' => strtoupper($request->tipo),
                    ]
                );
            return $last_id;
        } catch (\Throwable $th) {
            return 0;
        }
    }

     //actualizo un tipo de servicio
     public function update_tipo(Request $request,$id){
        try {
            DB::table('tipo_servicios')->where('id',$request->id_tipo)->update(
                [
                    'tipo' => strtoupper($request->tipo),
                ]
            );
            return $id;
        } catch (\Throwable $th) {
            return 0;
        }
    }


    public function delete_tipo($id){
        try {
            DB::table('tipo_servicios')->where('id', '=', $id)->update(['status'=>0]);
            return $id;
        } catch (\Throwable $th) {
            return -1;
        }
    }


}
