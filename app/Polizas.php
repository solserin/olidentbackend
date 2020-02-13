<?php

namespace App;

use App\Ventas;
use App\Beneficiarios;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Polizas extends Model
{
    protected $table = 'polizas';


    public function ventas()
    {
        return $this->hasMany('App\Ventas', 'polizas_id', 'num_poliza');
    }
    public function beneficiarios()
    {
        return $this->hasMany('App\Beneficiarios', 'polizas_id', 'num_poliza');
    }

    public function ruta()
    {
        return $this->hasOne('App\Rutas', 'id', 'rutas_id');
    }




    //aqui guardo los datos de la poliza
    public function guardar_poliza(Request $request)
    {
        try {
            DB::beginTransaction();
            //registrando la poliza
            $id_poliza = DB::table('polizas')->insertGetId(
                [
                    'num_poliza' => $request->num_poliza,
                    'fecha_afiliacion' => $request->fecha_afiliacion,
                    'usuario_capturo_id' => $request->usuario_registro_id,
                    'rutas_id' => $request->ruta_id['id'],
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
                    'num_beneficiario' => 1,
                    'nombre' => $request->titular,
                    'colonia' => $request->colonia,
                    'calle' => $request->calle,
                    'numero' => $request->numero,
                    'edad' => $request->edad,
                    'ocupacion' => $request->ocupacion,
                    'cp' => $request->cp,
                    'email' => $request->email,
                    'telefono' => $request->telefono,
                    'fotografia' => $base64,
                    'tipo_beneficiarios_id' => 1, //1 de beneficiario titular
                    'polizas_id' => $request->num_poliza,
                    'localidad_id' => $request->localidad_id,
                ]
            );
            //registrando beneficiarios
            for ($i = 0; $i < $request->tipo_poliza_id['numero_beneficiarios']; $i++) {
                DB::table('beneficiarios')->insert(
                    [
                        'num_beneficiario' => ($i + 2),
                        'nombre' => $request->beneficiarios[$i]['nombre'],
                        'edad' => $request->beneficiarios[$i]['edad'],
                        'fotografia' => $base64,
                        'tipo_beneficiarios_id' => 2, //1 de beneficiario titular
                        'polizas_id' => $request->num_poliza,
                        'localidad_id' => $request->localidad_id,
                    ]
                );
            }

            //registro venta
            $id_venta = DB::table('ventas')->insertGetId(
                [
                    'fecha_registro' => Carbon::now()->format('Y-m-d H:i:s'),
                    'fecha_venta' => $request->fecha_afiliacion,
                    'fecha_vencimiento' => Carbon::createFromDate($request->fecha_afiliacion)->addYear($request->tipo_poliza_id['duracion'])->format('Y-m-d H:i:s'),
                    'tipos_venta_id' => 1, //tipo de venta de afiliacion
                    'vendedor_id' => $request->vendedor_id['id'],
                    'polizas_id' => $request->num_poliza,
                    'tipo_polizas_id' => $request->tipo_poliza_id['id'],
                    'num_beneficiarios' => $request->tipo_poliza_id['numero_beneficiarios'],
                    'total' => $request->tipo_poliza_id['precio'],
                    'abonado' => $request->abono,
                    'restante' => ($request->tipo_poliza_id['precio'] - $request->abono),
                    'comision_vendedor' => ($request->tipo_poliza_id['precio'] * .10), //10 % de comision
                ]
            );

            //registro abono en la tabla abonos
            if ($request->abono > 0) {
                //si se ingreso un valor mayor a cero en el abono inicial se debe de registrar
                DB::table('abonos')->insert(
                    [
                        'fecha_registro' => Carbon::now()->format('Y-m-d H:i:s'),
                        'fecha_abono' => $request->fecha_afiliacion,
                        'formas_pago_id' => 1, //efectivo por default
                        'cantidad' => $request->abono,
                        'cobrador_id' => $request->vendedor_id['id'], //id del vendedor que cobro el abono inicial
                        'usuario_capturo_id' => $request->usuario_registro_id,
                        'ventas_id' => $id_venta,
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


    //aqui modifico los datos de la poliza
    public function modificar_poliza(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            //en caso de ser poliza de renovacion actualizo la fecha de renovacion de la poliza
            $venta = Ventas::where('id', $id)->first();
            $abonado = 0;
            $restante = 0;
            if ($venta->status == 1) {
                //se procede con la actualizacion
                $abonado = $venta->abonado;
                $restante = $request->tipo_poliza_id['precio'] - $abonado;
            } else {
                //no procede porque ya esta dada de baja
                return -1;
            }

            //verifico que tipo de venta es (afiliaxion 1 o renoavion 2)
            if ($venta->tipos_venta_id == 1) {
                //modificamos la fecha de renovacion
                DB::table('polizas')->where('num_poliza', $request->num_poliza_original)->update(
                    [
                        'fecha_afiliacion' => $request->fecha_venta,
                    ]
                );
            }


            DB::table('polizas')->where('num_poliza', $request->num_poliza_original)->update(
                [
                    'rutas_id' => $request->ruta_id['id'],
                ]
            );


            //registrando los beneficiarios (titular)
            DB::table('beneficiarios')->where('polizas_id', $request->num_poliza_original)->where('tipo_beneficiarios_id', 1)->update(
                [
                    'nombre' => $request->titular,
                    'colonia' => $request->colonia,
                    'calle' => $request->calle,
                    'numero' => $request->numero,
                    'edad' => $request->edad,
                    'ocupacion' => $request->ocupacion,
                    'cp' => $request->cp,
                    'email' => $request->email,
                    'telefono' => $request->telefono,
                    'localidad_id' => $request->localidad_id,
                ]
            );


            //reviso que la venta ya tenga registrados los beneficiarios y 
            //en caso de no tenerlos los registro
            $beneficiarios = Beneficiarios::where('polizas_id', $request->num_poliza_original)->where('tipo_beneficiarios_id', 2)->first();
            if ($beneficiarios) {
                //si existen solamente los actualizo
                //modificando beneficiarios
                for ($i = 0; $i < $request->tipo_poliza_id['numero_beneficiarios']; $i++) {
                    DB::table('beneficiarios')->where('num_beneficiario', ($i + 2))->where('polizas_id', $request->num_poliza_original)->where('tipo_beneficiarios_id', 2)->update(
                        [
                            'nombre' => $request->beneficiarios[$i]['nombre'],
                            'edad' => $request->beneficiarios[$i]['edad'],
                        ]
                    );
                }
            } else {
                $path =  public_path('images/profile.png');
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                //si no existen los registro
                for ($i = 0; $i < $request->tipo_poliza_id['numero_beneficiarios']; $i++) {
                    DB::table('beneficiarios')->insert(
                        [
                            'num_beneficiario' => ($i + 2),
                            'nombre' => $request->beneficiarios[$i]['nombre'],
                            'edad' => $request->beneficiarios[$i]['edad'],
                            'fotografia' => $base64,
                            'tipo_beneficiarios_id' => 2, //1 de beneficiario titular
                            'polizas_id' => $request->num_poliza_original,
                            'localidad_id' => $request->localidad_id,
                        ]
                    );
                }
            }


            //actualizo venta venta
            DB::table('ventas')->where('id', $id)->update(
                [
                    'fecha_venta' => $request->fecha_venta,
                    'fecha_vencimiento' => Carbon::createFromDate($request->fecha_venta)->addYear($request->tipo_poliza_id['duracion'])->format('Y-m-d H:i:s'),
                    'vendedor_id' => $request->vendedor_id['id'],
                    'polizas_id' => $request->num_poliza_original,
                    'tipo_polizas_id' => $request->tipo_poliza_id['id'],
                    'num_beneficiarios' => $request->tipo_poliza_id['numero_beneficiarios'],
                    'total' => $request->tipo_poliza_id['precio'],
                    'restante' => $restante,
                    //'comision_vendedor' =>($request->tipo_poliza_id['precio']*.10),//10 % de comision
                ]
            );


            /** AQUI AL FINAL VERIFICO SI CAMBIO EL NUMERO DE POLIZA ORIGINAL Y CAMBIO LA POLIZA, VENTAS Y ABONOS
             * ASOCIADOA AL ANTIGUO NUM DE POLIZA  AL NUEVO NUM DE POLIZA
             */
            if ($request->num_poliza != $request->num_poliza_original) {
                //quiere decir que la poliza cambio de numero y debe actualizarce todos los campos
                //relacionados al antiguro numero

                DB::table('polizas')->where('num_poliza', $request->num_poliza_original)->update(
                    [
                        'num_poliza' => $request->num_poliza
                    ]
                );

                DB::table('beneficiarios')->where('polizas_id', $request->num_poliza_original)->update(
                    [
                        'polizas_id' => $request->num_poliza
                    ]
                );

                DB::table('ventas')->where('polizas_id', $request->num_poliza_original)->update(
                    [
                        'polizas_id' => $request->num_poliza
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







    //aqui renovo una poliza
    public function renovar_poliza(Request $request)
    {
        try {
            DB::beginTransaction();
            DB::table('polizas')->where('num_poliza', $request->num_poliza)->update(
                [
                    'rutas_id' => $request->ruta_id['id'],
                ]
            );

            $venta = Ventas::where('polizas_id', $request->num_poliza)->orderBy('id', 'desc')->first();
            if ($venta->status == 1 && Carbon::createFromDate($venta->fecha_vencimiento)->format('Y-m-d') > Carbon::now()->format('Y-m-d')) {
                //no procede porque no esta cancelada ni tampoco vencida
                return -1;
            }

            //valido que la poliza ya haya sido pagada antes de renovar, esto solo en caso de que no haya sido canceladas
            if ($venta->status == 1 && $venta->restante > 0) {
                //no procede porque no ha pagado la poliza anterior
                return -2;
            }


            //registrando los beneficiarios (titular)
            DB::table('beneficiarios')->where('polizas_id', $request->num_poliza)->where('tipo_beneficiarios_id', 1)->update(
                [
                    'nombre' => $request->titular,
                    'colonia' => $request->colonia,
                    'calle' => $request->calle,
                    'numero' => $request->numero,
                    'edad' => $request->edad,
                    'ocupacion' => $request->ocupacion,
                    'cp' => $request->cp,
                    'email' => $request->email,
                    'telefono' => $request->telefono,
                    'localidad_id' => $request->localidad_id,
                ]
            );


            //reviso que la venta ya tenga registrados los beneficiarios y 
            //en caso de no tenerlos los registro
            $beneficiarios = Beneficiarios::where('polizas_id', $request->num_poliza)->where('tipo_beneficiarios_id', 2)->first();
            if ($beneficiarios) {
                //si existen solamente los actualizo
                //modificando beneficiarios
                for ($i = 0; $i < $request->tipo_poliza_id['numero_beneficiarios']; $i++) {
                    DB::table('beneficiarios')->where('num_beneficiario', ($i + 2))->where('polizas_id', $request->num_poliza)->where('tipo_beneficiarios_id', 2)->update(
                        [
                            'nombre' => $request->beneficiarios[$i]['nombre'],
                            'edad' => $request->beneficiarios[$i]['edad'],
                        ]
                    );
                }
            } else {
                $path =  public_path('images/profile.png');
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                //si no existen los registro
                for ($i = 0; $i < $request->tipo_poliza_id['numero_beneficiarios']; $i++) {
                    DB::table('beneficiarios')->insert(
                        [
                            'num_beneficiario' => ($i + 2),
                            'nombre' => $request->beneficiarios[$i]['nombre'],
                            'edad' => $request->beneficiarios[$i]['edad'],
                            'fotografia' => $base64,
                            'tipo_beneficiarios_id' => 2, //1 de beneficiario titular
                            'polizas_id' => $request->num_poliza,
                            'localidad_id' => $request->localidad_id,
                        ]
                    );
                }
            }

            $id_venta = DB::table('ventas')->insertGetId(
                [
                    'fecha_registro' => Carbon::now()->format('Y-m-d H:i:s'),
                    'fecha_venta' => $request->fecha_venta,
                    'fecha_vencimiento' => Carbon::createFromDate($request->fecha_venta)->addYear($request->tipo_poliza_id['duracion'])->format('Y-m-d H:i:s'),
                    'tipos_venta_id' => 2, //tipo de venta de renovacion
                    'vendedor_id' => $request->vendedor_id['id'],
                    'polizas_id' => $request->num_poliza,
                    'tipo_polizas_id' => $request->tipo_poliza_id['id'],
                    'num_beneficiarios' => $request->tipo_poliza_id['numero_beneficiarios'],
                    'total' => $request->tipo_poliza_id['precio'],
                    'abonado' => $request->abono,
                    'restante' => ($request->tipo_poliza_id['precio'] - $request->abono),
                    'comision_vendedor' => ($request->tipo_poliza_id['precio'] * .10), //10 % de comision
                ]
            );

            //registro abono en la tabla abonos
            if ($request->abono > 0) {
                //si se ingreso un valor mayor a cero en el abono inicial se debe de registrar
                DB::table('abonos')->insert(
                    [
                        'fecha_registro' => Carbon::now()->format('Y-m-d H:i:s'),
                        'fecha_abono' => $request->fecha_venta,
                        'formas_pago_id' => 1, //efectivo por default
                        'cantidad' => $request->abono,
                        'cobrador_id' => $request->vendedor_id['id'], //id del vendedor que cobro el abono inicial
                        'usuario_capturo_id' => $request->usuario_registro_id,
                        'ventas_id' => $id_venta,
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









    //aqui cancelo una poliza
    public function cancelar_poliza($id)
    {
        try {
            DB::beginTransaction();
            $venta = Ventas::where('id', $id)->first();
            if ($venta->status == 1 && Carbon::createFromDate($venta->fecha_vencimiento)->format('Y-m-d') > Carbon::now()->format('Y-m-d')) {
                //procede porque no esta cancelada ni tampoco vencida
                DB::table('ventas')->where('id', $id)->update(
                    [
                        'status' => 0,
                        'fecha_cancelacion' => Carbon::now()->format('Y-m-d H:i:s')
                    ]
                );
            } else {
                // ya ha sido cancelada o esta vencida la poliza
                return -1;
            }
            DB::commit();
            return $id;
        } catch (\Throwable $th) {
            DB::rollBack();
            return 0;
        }
    }
}