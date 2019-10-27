<?php

namespace App\Http\Controllers;

use App\Abonos;
use App\Ventas;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Api\ApiController;

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
}
