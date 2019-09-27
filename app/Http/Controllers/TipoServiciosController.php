<?php

namespace App\Http\Controllers;

use App\TipoServicios;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Api\ApiController;
use App\Servicios;

class TipoServiciosController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $key = Input::get('filter');
        return $this->showAllPaginated(TipoServicios::withCount('servicios')->where('tipo', 'like', '%'.$key.'%')->where('status',1)->orderBy('id','desc')->get());
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
          'tipo' => 'required|unique:tipo_servicios,tipo',
        ],
        [
          'required' => 'Este dato es obligatorio.',
          'unique' => 'Ya existe un tipo de servicio con este nombre, ingrese uno diferente.',
        ]
      );
      //aqui guardo el tipo de servicio
      $obj = new TipoServicios();
      $resultado=$obj->guardar_tipo($request);
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
        return $this->showOne(TipoServicios::where('id', $id)->first());
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
       //validacion de datos para el nuevo tipo de servicio
       request()->validate(
            [
            'tipo' => ['required',Rule::unique('tipo_servicios','tipo')->ignore($id)],
            ],
            [
                'required' => 'Este dato es obligatorio.',
                'unique' => 'Ya existe un tipo de servicio con este nombre, ingrese uno diferente.',
            ]
        );
        //aqui actualizo el usuario
        $obj = new TipoServicios();
        $resultado=$obj->update_tipo($request,$id);
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
        //verifico que no tenga usuarios asociados
        $res=TipoServicios::withCount('servicios')->where('id', $id)->first();
        if($res['servicios_count']>0){
          //tiene usuarios asociados y no se puede eliminar
          return -1;
        }else{
          //se puede eliminar
          $obj = new TipoServicios();
          $resultado=$obj->delete_tipo($id);
          return $resultado;
        }
        return $res;
    }


    //regresa los tipos de servicios que existen
    public function get_tipos(){
        return $this->showAll(TipoServicios::select('id','tipo')->where('status',1)->orderBy('id','asc')->get());
    }


}
