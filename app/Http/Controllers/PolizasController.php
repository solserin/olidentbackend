<?php

namespace App\Http\Controllers;

use App\Polizas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PolizasController extends Controller
{
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
         //validacion de datos para el nuevo usuario
       request()->validate(
        [
          'usuario_registro_id' => 'required|integer',
          'num_poliza' => 'required|integer|unique:polizas,num_poliza',
          'fecha_afiliacion' => 'required|date_format:Y-m-d',
          'tipo_poliza_id'=>'required',
          'ruta_id'=>'required',
          'vendedor_id'=>'required',
          'abono'=>'required|numeric|max:'.$request->tipo_poliza_id['precio'],
          'titular'=>'required',
          'colonia'=>'required',
          'calle'=>'required',
          'edad'=>'required',
          'numero'=>'required',
          'telefono'=>'required',
          'localidad_id'=>'required',
          'beneficiarios.*.nombre'=>Rule::requiredIf($request->tipo_poliza_id['id']>1),
          'beneficiarios.*.edad'=>Rule::requiredIf($request->tipo_poliza_id['id']>1),
        ],
        [
          'max'=>'La cantidad de abono no debe superar el precio de la póliza ('.$request->tipo_poliza_id['precio'].' Pesos.)',
          'required' => 'Este dato es obligatorio.',
          'date_format'=>'Seleccione una fecha válida.',
          'integer' => 'Este dato debe ser un numero entero.',
          'numeric' => 'Este dato debe ser un numero.',
          'email' => 'Debe ingresar un email',
          'unique' => 'Esta póliza ya existe, tal vez deba ir al formulario de renovación.',
        ]
      );
    
      //aqui guardo la poliza
      $obj = new Polizas();
      $resultado=$obj->guardar_poliza($request);
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
}
