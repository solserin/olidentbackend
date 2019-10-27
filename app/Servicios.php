<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Servicios extends Model
{
    protected $table = 'servicios';


     //relacion con el tipo de servicio al que pertenece (modelo tipoServicios)
     public function tipo()
     {
         return $this->belongsTo('App\TipoServicios','tipo_id','id');
     }


      //aqui guardo los datos del nuevo servicio
    public function guardar_servicio(Request $request){
        try {
            $last_id=DB::table('servicios')->insertGetId(
                    [
                         'servicio' => strtoupper($request->servicio),
                         'descripcion' =>strtoupper($request->descripcion),
                         'created_at' =>Carbon::now()->format('Y-m-d H:i:s'),
                         'precio_normal'=>$request->precio_normal,
                         'descuento_poliza'=>$request->descuento_poliza,
                         'tipo_precio_id'=>$request->tipo_precio_id,
                         'tipo_id'=>$request->tipo_id,
                    ]
                );
            return $last_id;
        } catch (\Throwable $th) {
            return 0;
        }
    }


     //actualizo un servicio
     public function update_servicio(Request $request,$id){
        try {
            DB::table('servicios')->where('id',$request->id_servicio)->update(
                [
                    'servicio' => strtoupper($request->servicio),
                    'descripcion' =>strtoupper($request->descripcion),
                    'updated_at' =>Carbon::now()->format('Y-m-d H:i:s'),
                    'precio_normal'=>$request->precio_normal,
                    'descuento_poliza'=>$request->descuento_poliza,
                    'tipo_precio_id'=>$request->tipo_precio_id,
                    'tipo_id'=>$request->tipo_id,
                ]
            );
            return $id;
        } catch (\Throwable $th) {
            return 0;
        }
    }

    
    public function delete_servicio($id){
        try {
            DB::table('servicios')->where('id', '=', $id)->update(['status'=>0]);
            return $id;
        } catch (\Throwable $th) {
            return -1;
        }
    }


}
