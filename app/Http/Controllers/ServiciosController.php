<?php

namespace App\Http\Controllers;

use App\Empresas;
use App\Servicios;
use App\TipoServicios;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Api\ApiController;

class ServiciosController extends ApiController
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $key = Input::get('filter');
        //return $this->showAllPaginated(Servicios::with('tipo')->select(['imagen','created_at','id','name','email','roles_id','status'])->where('name', 'like', '%'.$key.'%')->orderBy('id','desc')->get());
        return $this->showAllPaginated(Servicios::with('tipo')->where('servicio', 'like', '%'.$key.'%')->where('status','1')->orderBy('id','desc')->get());
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
          'servicio' => 'required|unique:servicios,servicio',
          'precio_normal' => 'required|numeric',
          'descuento_poliza' => 'required',
          'tipo_precio_id' => 'required',
          'tipo_id' => 'required',
        ],
        [
          'required' => 'Este dato es obligatorio.',
          'numeric' => 'Este dato debe ser un número (ejemplo: 1500)',
          'unique' => 'Ya existe un servicio con este nombre, ingrese uno diferente.',
        ]
      );
      //aqui guardo el rol nuevo
      $obj = new Servicios();
      $resultado=$obj->guardar_servicio($request);
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
        return $this->showOne(Servicios::select('id','servicio','descripcion','precio_normal','descuento_poliza','tipo_precio_id','tipo_id')->where('id', $id)->first());
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
            'servicio' => ['required',Rule::unique('servicios','servicio')->ignore($id)],
            'precio_normal' => 'required|numeric',
            'descuento_poliza' => 'required',
            'tipo_precio_id' => 'required',
            'tipo_id' => 'required',
            ],
            [
                'required' => 'Este dato es obligatorio.',
                'numeric' => 'Este dato debe ser un número (ejemplo: 1500)',
                'unique' => 'Ya existe un servicio con este nombre, ingrese uno diferente.',
            ]
        );
        //aqui actualizo el usuario
        $obj = new Servicios();
        $resultado=$obj->update_servicio($request,$id);
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
        //aqui deberia de validar si tiene algun tipo de operaciones asociadas para cuando exista el modulo de clinicas
        //se puede eliminar
        $obj = new Servicios();
        $resultado=$obj->delete_servicio($id);
        return $resultado;
    }


    public function get_reporte_servicios(){

        $servicios=TipoServicios::with('servicios')->orderBy('id','asc')->get();
        $empresa=DB::table('empresas')->where('id',1)->get();
        $pdf = PDF::loadView('servicios/servicios',compact('servicios','empresa'))->setPaper('a4', 'landscape');
        return $pdf->stream('archivo.pdf');
      }
   
}
