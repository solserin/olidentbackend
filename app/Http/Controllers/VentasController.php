<?php

namespace App\Http\Controllers;

use App\Abonos;
use App\Ventas;
use App\Polizas;
use App\Localidades;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Api\ApiController;
use PhpParser\Node\Stmt\Foreach_;

class VentasController extends ApiController
{

    public function __construct()
    {
        //$this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //verificar la cantidad maxima que puede pagar (para que no se pase del costo total)
        $maxima_cantidad = Ventas::where('id', $request->venta_id)->first();
        if (!$maxima_cantidad) {
            return $this->errorResponse('Dato no válido', 404);
        }
        //validacion de datos para el pago
        request()->validate(
            [
                'venta_id' => 'required|integer',
                'usuario_registro_id' => 'required|integer',
                'cobrador_id' => 'required',
                'abono' => 'required|numeric|max:' . $maxima_cantidad->restante . '|min:1',
                'fecha_abono' => 'required|date_format:Y-m-d',
            ],
            [
                'max' => 'La cantidad de abono no debe superar el valor restante de la póliza (' . $maxima_cantidad->restante . ' Pesos.)',
                'required' => 'Este dato es obligatorio.',
                'min' => 'El abono debe ser mínimo 1.00 Peso.',
                'date_format' => 'Seleccione una fecha válida.',
                'integer' => 'Este dato debe ser un numero entero.',
                'numeric' => 'Este dato debe ser un numero.',
            ]
        );
        //aqui guardo la poliza
        $obj = new Ventas();
        // el resultad regresa el numero de venta
        $resultado = $obj->guardar_abono($request, $maxima_cantidad);
        return $resultado;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $venta = Ventas::select(
            'ventas.id',
            'fecha_registro',
            'fecha_venta',
            'fecha_vencimiento',
            'tipos_venta_id',
            'vendedor_id',
            'polizas_id',
            'tipo_polizas_id',
            'num_beneficiarios',
            'total',
            'abonado',
            'restante',
            'comision_vendedor',
            'status',
            \DB::raw(
                'IF(ventas.fecha_vencimiento > ' . '"' . Carbon::now()->format('Y-m-d H:i:s') . '"' . ', "1","0") as estado_venta'
            )
        )
            ->with('tipo_poliza')
            ->with('vendedor:id,name')
            ->with(
                array('beneficiarios' => function ($query) {
                    $query->select('beneficiarios.id', 'polizas_id', 'beneficiarios.nombre', 'tipo_beneficiarios_id', 'calle', 'colonia', 'numero', 'cp', 'localidad_id', 'ocupacion', 'edad', 'telefono', 'localidades.nombre as localidad', 'municipios.nombre as municipio', 'email')
                        ->join('localidades', 'beneficiarios.localidad_id', '=', 'localidades.id')
                        ->join('municipios', 'localidades.municipio_id', '=', 'municipios.id')
                        ->orderBy('id', 'asc');
                })
            )
            ->with(
                array('poliza_origen' => function ($query) {
                    $query
                        //->select('polizas.rutas_id','num_poliza','ruta','fecha_afiliacion')
                        ->join('rutas', 'polizas.rutas_id', '=', 'rutas.id');
                })
            )
            ->orderBy('id', 'desc')
            ->where('polizas_id', $id)
            ->first();
        return $venta;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function estado_cuenta($id)
    {
        return $this->showAll(
            Ventas::with(
                array('abonos' => function ($query) {
                    $query
                        ->select('abonos.id', 'ventas_id', 'cantidad', 'users.name', 'fecha_abono', 'abonos.status')
                        ->join('users', 'abonos.cobrador_id', '=', 'users.id')
                        ->orderBy('id', 'asc');
                })
            )
                ->with('tipo_poliza:id,tipo')
                ->with('vendedor:id,name')
                ->with(
                    array('poliza_origen' => function ($query) {
                        $query
                            ->select('polizas.rutas_id', 'num_poliza', 'ruta')
                            ->join('rutas', 'polizas.rutas_id', '=', 'rutas.id');
                    })
                )
                ->orderBy('polizas_id', 'desc')
                ->where('id', $id)
                ->get()
        );
    }


    public function cancelar_pago(Request $request)
    {
        request()->validate(
            [
                'pago_id' => 'required|integer',
            ],
            [
                'required' => 'Este dato es obligatorio.',
                'integer' => 'Este dato debe ser un numero entero.',
            ]
        );
        $venta = Abonos::where('id', $request->pago_id)
            ->with('venta')
            ->first();
        if (!$venta) {
            return $this->errorResponse('Dato no válido', 404);
        }
        $obj = new Ventas();
        $resultado = $obj->cancelar_pago($request, $venta);
        return $resultado;
    }



    //REPORTES DE PAGOS
    public function reporte_especifico_pagos()
    {
        $empresa = DB::table('empresas')->where('id', 1)->get()->toArray();

        //datos para obtener los resultados
        $fecha_inicio = Input::get('fecha_inicio');
        $fecha_fin = Input::get('fecha_fin');
        $tipo_polizas_id = Input::get('tipo_polizas_id');
        $pagos_estado = Input::get('pagos_estado');
        $rutas_id = Input::get('rutas_id');
        $cobrador_id = Input::get('cobrador_id');
        $capturo_id = Input::get('capturo_id');
        $tipo_ventas_id = Input::get('tipo_ventas_id');
        $fecha_captura = Input::get('fecha_captura');


        //return $tipo_ventas_id;
        //obtengo la lista de informacion
        $pagos = Abonos::select('polizas.num_poliza', 'abonos.cobrador_id as id_cobrador', 'rutas.ruta', 'tipo_polizas.tipo as tipoPoliza', 'tipos_venta.tipo as tipoVenta', 'ventas.id as ventaId', 'beneficiarios.nombre', 'abonos.id', 'name', DB::raw("(select name from users where id=abonos.usuario_capturo_id) as capturista"), 'fecha_abono', 'abonos.fecha_registro as fecha_captura', 'cantidad', 'abonos.status', 'tipos_venta.id as tipos_venta_id', 'tipo_polizas.tipo')
            ->join('users', 'abonos.cobrador_id', '=', 'users.id')
            ->join('ventas', 'abonos.ventas_id', '=', 'ventas.total')
            ->join('tipos_venta', 'ventas.tipos_venta_id', '=', 'tipos_venta.id')
            ->join('polizas', 'ventas.polizas_id', '=', 'polizas.num_poliza')
            ->join('rutas', 'rutas.id', '=', 'polizas.rutas_id')
            ->join('tipo_polizas', 'ventas.tipo_polizas_id', '=', 'tipo_polizas.id')
            ->join('beneficiarios', 'beneficiarios.polizas_id', '=', 'polizas.num_poliza')
            ->where('beneficiarios.tipo_beneficiarios_id', '=', '1')
            ->where('fecha_abono', '>=', $fecha_inicio)
            ->where('fecha_abono', '<=', $fecha_fin)
            //->where('tipos_venta.id',$tipo_ventas_id)
            ->where(function ($q) use ($tipo_ventas_id) {
                if ($tipo_ventas_id) {
                    $q->where('tipos_venta.id', $tipo_ventas_id);
                }
            })
            ->where(function ($q) use ($tipo_polizas_id) {
                if ($tipo_polizas_id) {
                    $q->where('tipo_polizas.id', $tipo_polizas_id);
                }
            })
            ->where(function ($q) use ($pagos_estado) {
                if ($pagos_estado != "") {
                    $q->where('abonos.status', $pagos_estado);
                }
            })
            ->where(function ($q) use ($rutas_id) {
                if ($rutas_id != "") {
                    $q->where('rutas.id', $rutas_id);
                }
            })
            ->where(function ($q) use ($cobrador_id) {
                if ($cobrador_id != "") {
                    $q->where('abonos.cobrador_id', $cobrador_id);
                }
            })
            ->where(function ($q) use ($capturo_id) {
                if ($capturo_id != "") {
                    $q->where('abonos.usuario_capturo_id', $capturo_id);
                }
            })
            ->where(function ($q) use ($fecha_captura) {
                if (trim($fecha_captura) != "") {
                    $q->where('abonos.fecha_registro', $fecha_captura);
                }
            })
            //->orderBy('rutas.id', 'asc')
            ->orderBy('abonos.cobrador_id', 'asc')
            ->get();

        //si no lo esta mandando imprimir retorna el json
        if (!Input::get('imprimir')) {
            //retorna el json
            return $pagos;
        } else {
            // return $pagos;
            $img = getB64Image($empresa[0]->logo);
            // Obtener la extensión de la Imagen
            $img_extension = getB64Extension($empresa[0]->logo);
            // Crear un nombre aleatorio para la imagen
            $img_name = 'logo' . time() . '.' . $img_extension;
            // Usando el Storage guardar en el disco creado anteriormente y pasandole a
            // la función "put" el nombre de la imagen y los datos de la imagen como
            // segundo parametro
            Storage::disk('images_base64')->put($img_name, $img);
            $file = storage_path('app/images_base64/' . $img_name);
            $pdf = PDF::loadView('reportes/pagos_especifico', compact('empresa', 'file', 'pagos', 'fecha_inicio', 'fecha_fin'))->setPaper('a4', 'landscape');
            return $pdf->download('archivo.pdf');
        }
    }





    //REPORTES DE PAGOS
    public function reporte_cobranza()
    {
        $empresa = DB::table('empresas')->where('id', 1)->get()->toArray();
        //datos para obtener los resultados
        $fecha_inicio = Input::get('fecha_inicio');
        $fecha_fin = Input::get('fecha_fin');
        $rutas_id = Input::get('rutas_id');


        //return $tipo_ventas_id;
        //obtengo la lista de informacion
        $pagos = Abonos::select('name','ventas.total','ventas.abonado','ventas.restante','ventas.id as idVenta', 'fecha_abono', 'ventas.polizas_id', 'beneficiarios.nombre', 'ventas.total', 'ruta', 'abonos.id as aid','fecha_abono as fab', DB::raw("(select @importe:=(ventas.total-IFNULL(SUM(cantidad), 0)) from abonos where ventas_id=ventas.id and fecha_abono<fab and abonos.status=1 order by id asc) as importe,(@importe)-cantidad as saldo"),DB::raw("(select @cuantos:=(count(abonos.id)) from abonos where ventas_id=ventas.id and fecha_abono<fab and abonos.status=1 order by id asc) as cuantos"), 'cantidad',DB::raw("(select @total_ruta:=(sum(ventas.restante)) from ventas where ventas.status=1) as total_ruta"))
            ->join('ventas', 'abonos.ventas_id', '=', 'ventas.id')
            ->join('polizas', 'polizas.num_poliza', '=', 'ventas.polizas_id')
            ->join('rutas', 'polizas.rutas_id', '=', 'rutas.id')
            ->join('beneficiarios', 'beneficiarios.polizas_id', '=', 'ventas.polizas_id')
            ->join('users', 'rutas.cobrador_id', '=', 'users.id')
            ->where('beneficiarios.tipo_beneficiarios_id', '=', '1')
            ->where('abonos.status', '=', 1)
            ->where('fecha_abono', '>=', $fecha_inicio)
            ->where('fecha_abono', '<=', $fecha_fin)
            ->where(function ($q) use ($rutas_id) {
                if ($rutas_id != "") {
                    $q->where('rutas.id', $rutas_id);
                }
            })
            ->orderBy('abonos.fecha_abono', 'desc')
            ->get();


         //obtengo los datos para el valor total de la ruta
         $polizas=Polizas::
        select('polizas.id','num_poliza','rutas_id','ruta')
        ->with(
            array('ventas' => function ($query) {
                $query->select('ventas.polizas_id','ventas.id','nombre','total','abonado','restante','fecha_venta','fecha_vencimiento')
                ->join('beneficiarios', 'beneficiarios.polizas_id', '=', 'ventas.polizas_id')
                ->where('beneficiarios.tipo_beneficiarios_id', '=', '1')
                ->orderBy('ventas.id', 'desc');
            })
        )
        ->join('rutas', 'polizas.rutas_id', '=', 'rutas.id')
        ->where('rutas_id', '=',  $rutas_id)
        ->orderBy('num_poliza', 'desc')
        ->get();

        $total_ruta=0;
        foreach ($polizas as $poliza) {
            $total_ruta=$total_ruta+$poliza->ventas[0]->restante;
        }
         //fin de calculo para la ruta



        //si no lo esta mandando imprimir retorna el json
        if (!Input::get('imprimir')) {
            //retorna el json
            return $pagos;
        }else{
            //si se va imprimir
             $img = getB64Image($empresa[0]->logo);
             // Obtener la extensión de la Imagen
             $img_extension = getB64Extension($empresa[0]->logo);
             // Crear un nombre aleatorio para la imagen
             $img_name = 'logo' . time() . '.' . $img_extension;
             // Usando el Storage guardar en el disco creado anteriormente y pasandole a
             // la función "put" el nombre de la imagen y los datos de la imagen como
             // segundo parametro
             Storage::disk('images_base64')->put($img_name, $img);
             $file = storage_path('app/images_base64/' . $img_name);
             $pdf = PDF::set_option("isPhpEnabled", true)->loadView('reportes/reporte_cobranza', compact('empresa', 'file','total_ruta', 'pagos', 'fecha_inicio', 'fecha_fin'))->setPaper('a4', 'portrait');
             return $pdf->stream('archivo.pdf');

        }
    }


    public function ruta_completa()
    {
        $empresa = DB::table('empresas')->where('id', 1)->get()->toArray();
        //datos para obtener los resultados
        $rutas_id = Input::get('rutas_id');
        $polizas=Polizas::
        select('polizas.id','num_poliza','rutas_id','ruta')
        ->with(
            array('ventas' => function ($query) {
                $query->select('ventas.polizas_id','ventas.id','nombre','total','abonado','restante','fecha_venta','fecha_vencimiento')
                ->join('beneficiarios', 'beneficiarios.polizas_id', '=', 'ventas.polizas_id')
                ->where('beneficiarios.tipo_beneficiarios_id', '=', '1')
                ->orderBy('ventas.id', 'desc');
            })
        )
        ->join('rutas', 'polizas.rutas_id', '=', 'rutas.id')
        ->where('rutas_id', '=',  $rutas_id)
        //->where('polizas.num_poliza', '=', 874)
        ->orderBy('num_poliza', 'desc')
        ->get();
        //si no lo esta mandando imprimir retorna el json
        if (!Input::get('imprimir')) {
            //retorna el json
            return $this->showAllPaginated($polizas);
        }
    }
}
