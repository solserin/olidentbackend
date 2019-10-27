<?php

namespace App\Http\Controllers;

use App\User;
use App\Rutas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Api\ApiController;
use App\Localidades;

class RutasController extends ApiController
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $key = Input::get('filter');
        //return $key;
        return $this->showAllPaginated(Rutas::with('cobrador')->where('ruta', 'like', '%'.$key.'%')->where('status', '=','1')->orderBy('id','desc')->get());
    }
    //trae todas las rutas existentes
    public function get_rutas_disponibles()
    {
        //original return $this->showAll(Rutas::with('cobrador')->where('status', '=','1')->orderBy('id','asc')->get());
        return $this->showAll(Rutas::where('status', '=','1')->orderBy('id','asc')->get());
    }
    
    public function localidad()
    {
        $key = Input::get('filter');
        //return $key;
        return $this->showAll(Localidades::select('id','nombre','municipio_id')->with('municipio')->where('nombre', 'like', '%'.$key.'%')
        ->whereIn('municipio_id',[1889,1891,1881,1886])->get());
    }

    //regresa los usuarios que 
    public function get_cobradores()
    {
        //rol id 3 de cobradores
        return $this->showAll(User::select('id','name')->where('roles_id', '=', '3')->where('status', '=','1')->orderBy('id','asc')->get());
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
        //validacion de datos para la nueva ruta
       request()->validate(
            [
            'ruta' => 'required|unique:rutas,ruta',
            'descripcion' => 'required',
            'cobrador_id' => 'required'
            ],
            [
            'required' => 'Esta dato es requerido.',
            'unique' => 'Ya existe una ruta con este nombre, ingrese otra diferente',
            ]
        );

        //aqui guardo el rol nuevo
        $obj = new Rutas();
        $resultado=$obj->guardar_ruta($request);
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
        return $this->showOne(Rutas::where('id', $id)->first());
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
         //validacion de datos de la ruta
         request()->validate(
            [
              'cobrador_id' => 'required',
              'ruta' => ['required', Rule::unique('rutas')->ignore($id)],
              'descripcion' => 'required',
            ],
            [
                'required' => 'Esta dato es requerido.',
                'unique' => 'Ya existe una ruta con este nombre, ingrese otra diferente',
            ]
          );
          //aqui guardo el rol nuevo
          $obj = new Rutas();
          $resultado=$obj->update_ruta($request,$id);
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
        //validar mas adelante si tiene polizas asociadas
        $obj = new Rutas();
        $resultado=$obj->delete_ruta($id);
        return $resultado;
        
    }
}
