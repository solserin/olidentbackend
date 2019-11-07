<?php

namespace App\Http\Controllers;

use App\Beneficiarios;
use App\Ventas;
use App\Polizas;
use App\Localidades;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Api\ApiController;

class PolizasController extends ApiController
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
    $key = Input::get('filter');
    $parametro = Input::get('parametro');
    $num_poliza = '';
    $titular = '';
    if ($parametro == 1) {
      //si es 1 si es por numero de poliza
      $num_poliza = $key;
    } else if ($parametro == 2) {
      //si es 2 si es por titular
      $titular = $key;
    }

    $datos = Polizas::with(
      array('ventas' => function ($query) {
        $query->select(
          'tipos_venta.tipo as tipoVenta',
          'fecha_venta',
          'tipo_polizas.tipo',
          'tipo_polizas_id',
          'ventas.id',
          'polizas_id',
          'vendedor_id',
          'abonado',
          'total',
          'restante',
          'name',
          'fecha_vencimiento',
          'ventas.status',
          \DB::raw(
            'IF(ventas.fecha_vencimiento > ' . '"' . Carbon::now()->format('Y-m-d H:i:s') . '"' . ', "1","0") as estado_venta'
          )
        )
          ->join('users', 'users.id', '=', 'ventas.vendedor_id')
          ->join('tipos_venta', 'tipos_venta.id', '=', 'ventas.tipos_venta_id')
          ->join('tipo_polizas', 'tipo_polizas.id', '=', 'ventas.tipo_polizas_id')
          ->orderBy('id', 'desc');
      })
    )
      ->with('ruta')
      ->withCount(
        array('ventas as estado_servicio' => function ($query) {
          $query->where('fecha_vencimiento', '>', Carbon::now()->format('Y-m-d H:i:s'))
            ->where('status', '=', 1);
        })
      )
      ->withCount('ventas as total_ventas')
      ->with(
        array('beneficiarios' => function ($query) {
          $query
            ->select('beneficiarios.id', 'nombre', 'polizas_id', 'edad', 'tipo_beneficiarios_id', 'tipo')
            ->join('tipo_beneficiarios', 'tipo_beneficiarios.id', '=', 'beneficiarios.tipo_beneficiarios_id')
            ->where('tipo_beneficiarios_id', '1')
            ->orderBy('id', 'asc');
        })
      )
      ->join('beneficiarios', 'polizas.num_poliza', '=', 'beneficiarios.polizas_id')
      ->where('beneficiarios.nombre', 'like', '%' . $titular . '%')
      ->when($num_poliza != '', function ($q) use ($num_poliza) {
        return $q->where('num_poliza', '=', $num_poliza);
      })
      ->orderBy('num_poliza', 'desc')
      ->distinct()->get();
    return $this->showAllPaginated($datos);
  }

  ////////funciones para filtrar los datos de consultar polizas

  ///fin de funciones para consultar polizas




  public function beneficiario()
  {
    $key = Input::get('filter');
    //return $key;
    return $this->showAll(Beneficiarios::select('id', 'nombre', 'polizas_id', 'colonia', 'calle', 'numero')->where('nombre', 'like', '%' . $key . '%')
      ->where('tipo_beneficiarios_id', 1)->get());
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
    //validacion de datos para el nuevo usuario
    request()->validate(
      [
        'usuario_registro_id' => 'required|integer',
        'num_poliza' => 'required|integer|unique:polizas,num_poliza',
        'fecha_afiliacion' => 'required|date_format:Y-m-d',
        'tipo_poliza_id' => 'required',
        'ruta_id' => 'required',
        'vendedor_id' => 'required',
        'abono' => 'required|numeric|max:' . $request->tipo_poliza_id['precio'] . '|min:0',
        'titular' => 'required',
        'colonia' => 'required',
        'calle' => 'required',
        'edad' => 'required',
        'numero' => 'required',
        'telefono' => 'required',
        'localidad_id' => 'required',
        'beneficiarios.*.nombre' => Rule::requiredIf($request->tipo_poliza_id['id'] > 1),
        'beneficiarios.*.edad' => Rule::requiredIf($request->tipo_poliza_id['id'] > 1),
      ],
      [
        'max' => 'La cantidad de abono no debe superar el precio de la póliza (' . $request->tipo_poliza_id['precio'] . ' Pesos.)',
        'min' => 'El abono debe ser mínimo 0 Pesos.',
        'required' => 'Este dato es obligatorio.',
        'date_format' => 'Seleccione una fecha válida.',
        'integer' => 'Este dato debe ser un numero entero.',
        'numeric' => 'Este dato debe ser un numero.',
        'email' => 'Debe ingresar un email',
        'unique' => 'Esta póliza ya existe, tal vez deba ir al formulario de renovación.',
      ]
    );

    //aqui guardo la poliza
    $obj = new Polizas();
    // el resultad regresa el numero de venta
    $resultado = $obj->guardar_poliza($request);
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
    //
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
    //validacion de datos para el nuevo usuario
    request()->validate(
      [
        'usuario_registro_id' => 'required|integer',
        'num_poliza' => 'required|integer',
        'fecha_venta' => 'required|date_format:Y-m-d',
        'tipo_poliza_id' => 'required',
        'ruta_id' => 'required',
        'vendedor_id' => 'required',
        'titular' => 'required',
        'colonia' => 'required',
        'calle' => 'required',
        'edad' => 'required',
        'numero' => 'required',
        'telefono' => 'required',
        'localidad_id' => 'required',
        'beneficiarios.*.nombre' => Rule::requiredIf($request->tipo_poliza_id['id'] > 1),
        'beneficiarios.*.edad' => Rule::requiredIf($request->tipo_poliza_id['id'] > 1),
      ],
      [
        'required' => 'Este dato es obligatorio.',
        'date_format' => 'Seleccione una fecha válida.',
        'integer' => 'Este dato debe ser un numero entero.',
        'numeric' => 'Este dato debe ser un numero.',
        'email' => 'Debe ingresar un email',
      ]
    );

    //aqui guardo la poliza
    $obj = new Polizas();
    // el resultad regresa el numero de venta
    $resultado = $obj->modificar_poliza($request, $id);
    return $resultado;
  }

  public function cancelar_poliza(Request $request, $id)
  {
    //validacion de datos para el nuevo usuario
    request()->validate(
      [
        'usuario_registro_id' => 'required|integer',
        'num_poliza' => 'required|integer',
        'fecha_venta' => 'required|date_format:Y-m-d',
        'tipo_poliza_id' => 'required',
        'ruta_id' => 'required',
        'vendedor_id' => 'required',
        'titular' => 'required',
        'colonia' => 'required',
        'calle' => 'required',
        'edad' => 'required',
        'numero' => 'required',
        'telefono' => 'required',
        'beneficiarios.*.nombre' => Rule::requiredIf($request->tipo_poliza_id['id'] > 1),
        'beneficiarios.*.edad' => Rule::requiredIf($request->tipo_poliza_id['id'] > 1),
      ],
      [
        'required' => 'Este dato es obligatorio.',
        'date_format' => 'Seleccione una fecha válida.',
        'integer' => 'Este dato debe ser un numero entero.',
        'numeric' => 'Este dato debe ser un numero.',
        'email' => 'Debe ingresar un email',
      ]
    );

    //aqui guardo la poliza
    $obj = new Polizas();
    // el resultad regresa el numero de venta
    $resultado = $obj->cancelar_poliza($id);
    return $resultado;
  }


  public function renovar_poliza(Request $request)
  {
    request()->validate(
      [
        'usuario_registro_id' => 'required|integer',
        'num_poliza' => 'required|integer',
        'fecha_venta' => 'required|date_format:Y-m-d',
        'tipo_poliza_id' => 'required',
        'ruta_id' => 'required',
        'vendedor_id' => 'required',
        'abono' => 'required|numeric|max:' . $request->tipo_poliza_id['precio'] . '|min:0',
        'titular' => 'required',
        'colonia' => 'required',
        'calle' => 'required',
        'edad' => 'required',
        'numero' => 'required',
        'telefono' => 'required',
        'localidad_id' => 'required',
        'beneficiarios.*.nombre' => Rule::requiredIf($request->tipo_poliza_id['id'] > 1),
        'beneficiarios.*.edad' => Rule::requiredIf($request->tipo_poliza_id['id'] > 1),
      ],
      [
        'max' => 'La cantidad de abono no debe superar el precio de la póliza (' . $request->tipo_poliza_id['precio'] . ' Pesos.)',
        'min' => 'El abono debe ser mínimo 0 Pesos.',
        'required' => 'Este dato es obligatorio.',
        'date_format' => 'Seleccione una fecha válida.',
        'integer' => 'Este dato debe ser un numero entero.',
        'numeric' => 'Este dato debe ser un numero.',
        'email' => 'Debe ingresar un email',
        //'unique' => 'Esta póliza ya existe, tal vez deba ir al formulario de renovación.',
      ]
    );

    //aqui guardo la poliza
    $obj = new Polizas();
    // el resultad regresa el numero de venta
    $resultado = $obj->renovar_poliza($request);
    return $resultado;
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






  public function nota_venta()
  {
    $id_venta = Input::get('venta_id');
    $num_poliza = Input::get('poliza_id');
    if (!Input::get('venta_id') || !Input::get('poliza_id')) {
      return $this->errorResponse('Error, esta URL no existe', 404);
    }
    //eliminos los archivos anteriores
    $files = Storage::disk('images_base64')->files();
    foreach ($files as $fi) {
      Storage::disk('images_base64')->delete($fi);
    }

    $empresa = DB::table('empresas')->where('id', 1)->get()->toArray();
    //obtengo los datos de la venta
    $venta = Ventas::with('poliza')
      ->with('vendedor:id,name')
      ->with('tipo_venta')
      ->with('tipo_poliza')
      ->with('beneficiarios:id,nombre,polizas_id,tipo_beneficiarios_id,calle,colonia,numero,cp,localidad_id,ocupacion,edad,telefono')
      ->where('polizas_id', $num_poliza)
      ->where('id', $id_venta)
      ->get();
    if (!count($venta)) {
      return $this->errorResponse('Error, esta URL no existe', 404);
    }
    //return $venta;
    //si hay resultados de la venta cargo la localidad de la venta
    if (isset($venta[0]['beneficiarios'][0]['localidad_id'])) {
      $localidad = Localidades::with('municipio')->where('id', $venta[0]['beneficiarios'][0]['localidad_id'])
        ->get()
        ->toArray();
    } else {
      $localidad = '';
    }

    //vigente o vencida
    $estado_poliza_venta = 0;
    if (Carbon::createFromDate($venta[0]['fecha_vencimiento'])->format('Y-m-d') > Carbon::now()->format('Y-m-d')) {
      $estado_poliza_venta = 1;
    }

    //return $venta;
    //return $venta[0]['poliza']['id'];
    // Obtener los datos de la imagen
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
    $pdf = PDF::loadView('polizas/nota_venta', compact('empresa', 'file', 'venta', 'localidad', 'estado_poliza_venta'))->setPaper('a4');
    return $pdf->stream('archivo.pdf');
  }




  public function tarjeta_cobranza()
  {
    $id_venta = Input::get('venta_id');
    if (!Input::get('venta_id')) {
      return $this->errorResponse('Error, esta URL no existe', 404);
    }
    //eliminos los archivos anteriores
    $files = Storage::disk('images_base64')->files();
    foreach ($files as $fi) {
      Storage::disk('images_base64')->delete($fi);
    }
    $empresa = DB::table('empresas')->where('id', 1)->get()->toArray();
    //obtengo los datos de la venta
    $venta = Ventas::with('poliza')
      ->with('vendedor:id,name')
      ->with('abonos')
      ->with('tipo_venta')
      ->with('tipo_poliza')
      ->with('beneficiarios:id,nombre,polizas_id,tipo_beneficiarios_id,calle,colonia,numero,cp,localidad_id,ocupacion,edad,telefono')
      ->where('id', $id_venta)
      ->get();
    if (!count($venta)) {
      return $this->errorResponse('Error, esta URL no existe', 404);
    }
    //return $venta;
    //si hay resultados de la venta cargo la localidad de la venta
    if (isset($venta[0]['beneficiarios'][0]['localidad_id'])) {
      $localidad = Localidades::with('municipio')->where('id', $venta[0]['beneficiarios'][0]['localidad_id'])
        ->get()
        ->toArray();
    } else {
      $localidad = '';
    }

    //vigente o vencida
    $estado_poliza_venta = 0;
    if (Carbon::createFromDate($venta[0]['fecha_vencimiento'])->format('Y-m-d') > Carbon::now()->format('Y-m-d')) {
      $estado_poliza_venta = 1;
    }

    //return $venta;
    //return $venta[0]['poliza']['id'];
    // Obtener los datos de la imagen
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
    $pdf = PDF::loadView('polizas/tarjeta_cobranza', compact('empresa', 'file', 'venta', 'localidad', 'estado_poliza_venta'))->setPaper('a4','landscape');
    return $pdf->stream('archivo.pdf');
  }
}
