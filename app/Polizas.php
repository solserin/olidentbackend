<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Polizas extends Model
{
    protected $table = 'polizas';


    public function ventas(){
        return $this->hasMany('App\Ventas','polizas_id','num_poliza');
    }
    public function beneficiarios(){
        return $this->hasMany('App\Beneficiarios','polizas_id','num_poliza');
    }

    public function ruta(){
        return $this->hasOne('App\Rutas','id','rutas_id');
    }



    //aqui guardo los datos de la poliza
    public function guardar_poliza(Request $request){
        try {
            DB::beginTransaction();
            //registrando la poliza
            $id_poliza=DB::table('polizas')->insertGetId(
                [
                    'num_poliza' =>$request->num_poliza,
                    'fecha_afiliacion' =>$request->fecha_afiliacion,
                    'usuario_capturo_id' =>$request->usuario_registro_id,
                    'rutas_id' =>$request->ruta_id['id'],
                ]
            );
            //return $request->estado;
            $path =  public_path('images/profile.png');
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            //registrando los beneficiarios (titular)
            DB::table('beneficiarios')->insert(
                [
                    'nombre' =>$request->titular,
                    'colonia' =>$request->colonia,
                    'calle' =>$request->calle,
                    'numero' =>$request->numero,
                    'edad' =>$request->edad,
                    'ocupacion' =>$request->ocupacion,
                    'cp' =>$request->cp,
                    'email' =>$request->email,
                    'telefono' =>$request->telefono,
                    'fotografia' =>$base64,
                    'tipo_beneficiarios_id' =>1,//1 de beneficiario titular
                    'polizas_id' =>$request->num_poliza,
                    'localidad_id' =>$request->localidad_id,
                ]
            );
            //registrando beneficiarios
            for ($i=0; $i < $request->tipo_poliza_id['numero_beneficiarios']; $i++) { 
                DB::table('beneficiarios')->insert(
                    [
                        'nombre' =>$request->beneficiarios[$i]['nombre'],
                        'edad' =>$request->beneficiarios[$i]['edad'],
                        'fotografia' =>$base64,
                        'tipo_beneficiarios_id' =>2,//1 de beneficiario titular
                        'polizas_id' =>$request->num_poliza,
                        'localidad_id' =>$request->localidad_id,
                    ]
                );
            }

            //registro venta
            $id_venta=DB::table('ventas')->insertGetId(
                [
                    'fecha_registro' =>Carbon::now()->format('Y-m-d H:i:s'),
                    'fecha_venta' =>$request->fecha_afiliacion,
                    'fecha_vencimiento' =>Carbon::now()->addYear($request->tipo_poliza_id['duracion'])->format('Y-m-d H:i:s'),
                    'tipos_venta_id' =>1,//tipo de venta de afiliacion
                    'vendedor_id' =>$request->vendedor_id['id'],
                    'polizas_id' =>$request->num_poliza,
                    'tipo_polizas_id' =>$request->tipo_poliza_id['id'],
                    'num_beneficiarios' =>$request->tipo_poliza_id['numero_beneficiarios'],
                    'total' =>$request->tipo_poliza_id['precio'],
                    'abonado' =>$request->abono,
                    'restante' =>($request->tipo_poliza_id['precio']-$request->abono),
                    'comision_vendedor' =>($request->tipo_poliza_id['precio']*.10),//10 % de comision
                ]
            );

            //registro abono en la tabla abonos
            if($request->abono>0){
                //si se ingreso un valor mayor a cero en el abono inicial se debe de registrar
                DB::table('abonos')->insert(
                    [
                        'fecha_registro' =>Carbon::now()->format('Y-m-d H:i:s'),
                        'fecha_abono' =>$request->fecha_afiliacion,
                        'formas_pago_id' =>1,//efectivo por default
                        'cantidad' =>$request->abono,
                        'cobrador_id' =>$request->vendedor_id['id'],//id del vendedor que cobro el abono inicial
                        'usuario_capturo_id' =>$request->usuario_registro_id,
                        'ventas_id' =>$id_venta,
                    ]
                );
            }
            DB::commit();
            return $id_venta;
        } catch (\Throwable $th) {
            DB::rollBack();
            return 0;
        }
    }
}
