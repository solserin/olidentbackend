<?php

namespace App\Http\Controllers;

use App\GruposVendedores;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Api\ApiController;

class GruposVendedoresController extends ApiController
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

     //con esta funcion puedo agregar y modificar 
    public function index()
    {
        $key = Input::get('filter');
        return $this->showAllPaginated(GruposVendedores::withCount('vendedores')->where('id','>','1')->where('grupo', 'like', '%'.$key.'%')->where('status','1')->orderBy('id','desc')->get());
    }

    //con esta lista puedo agregar y modificar los usuarios
    public function get_agregar_modificar_lista()
    {
        return $this->showAll(GruposVendedores::where('status','1')->orderBy('id','asc')->get());
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
            'grupo' => 'required|unique:grupos_vendedores,grupo',
        ],
        [
          'required' => 'Este dato es obligatorio',
          'unique' => 'Este grupo ya ha sido registrado, debe ingresar uno diferente.',
        ]
      );

      //aqui guardo el rol nuevo
      $obj = new GruposVendedores();
      $resultado=$obj->guardar_grupo($request);
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
        return $this->showOne(GruposVendedores::where('id', 1)->first());
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
                'grupo' => ['required', Rule::unique('grupos_vendedores')->ignore($id)],
            ],
            [
                'required' => 'Este dato es obligatorio',
                'unique' => 'Este grupo ya ha sido registrado, debe ingresar uno diferente.',
            ]
          );
          //aqui guardo el rol nuevo
          $obj = new GruposVendedores();
          $resultado=$obj->update_grupo($request,$id);
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
         $res=GruposVendedores::withCount('vendedores')->where('id', $id)->first();
         if($res['vendedores_count']>0){
           //tiene usuarios asociados y no se puede eliminar
           return -1;
         }else{
           //se puede eliminar
           $obj = new GruposVendedores();
           $resultado=$obj->delete_grupo($id);
           return $resultado;
         }
         return $res;
    }
}
