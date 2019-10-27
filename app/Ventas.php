<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Ventas extends Model
{
    protected $table = 'ventas';

    public function poliza(){
        return $this->hasOne('App\Polizas','num_poliza','polizas_id');
    }

    public function vendedor(){
        return $this->hasOne('App\User','id','vendedor_id');
    }

    public function tipo_venta(){
        return $this->hasOne('App\TiposVenta','id','tipos_venta_id');
    }

    public function beneficiarios(){
        return $this->hasMany('App\Beneficiarios','polizas_id','polizas_id');
    }

    public function tipo_poliza(){
        return $this->hasOne('App\TiposPolizas','id','tipo_polizas_id');
    }

    public function abonos(){
        return $this->hasMany('App\Abonos','ventas_id','id');
    }

    public function poliza_origen(){
        return $this->hasOne('App\Polizas','num_poliza','polizas_id');
    }

     //aqui guardo los datos del abono
     public function guardar_abono(Request $request,$maxima_cantidad){
        try {
            DB::beginTransaction();
            //registrando la poliza
            $id_pago=DB::table('abonos')->insertGetId(
                [
                    'fecha_abono' =>$request->fecha_abono,
                    'formas_pago_id' =>1,
                    'cantidad' =>$request->abono,
                    'cobrador_id' =>$request->cobrador_id['id'],
                    'usuario_capturo_id' =>$request->usuario_registro_id,
                    'ventas_id' =>$request->venta_id,
                    'fecha_registro' =>Carbon::now()->format('Y-m-d H:i:s'),
                ]
            );

            //modificando el abonado y restante de la venta.
            DB::table('ventas')->where('id',$request->venta_id)->update(
                [
                    'abonado' =>($maxima_cantidad->abonado+$request->abono),
                    'restante' =>($maxima_cantidad->restante-$request->abono),
                ]
            );
            
            DB::commit();
            return $id_pago;
        } catch (\Throwable $th) {
            DB::rollBack();
            return 0;
        }
    }



     public function cancelar_pago(Request $request,$venta){
        try {
            DB::beginTransaction();
            //modificando el abonado y restante de la venta.
            DB::table('abonos')->where('id',$request->pago_id)->update(
                [
                    'status' =>0,
                ]
            );

            $nuevo_abonado=($venta['venta']->abonado)-$venta->cantidad;
            $nuevo_restante=($venta['venta']->restante)+$venta->cantidad;

            DB::table('ventas')->where('id',$venta['venta']->id)->update(
                [
                    'abonado' =>$nuevo_abonado,
                    'restante' =>$nuevo_restante,
                ]
            );
            DB::commit();
            return $request->pago_id;
        } catch (\Throwable $th) {
            DB::rollBack();
            return 0;
        }
    }


}
