<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Roles;

class RolesController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

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
            'rol' => 'required|unique:roles',
            'itemsPermisos' => 'required'
          ],
          [
            'rol.required' => 'Ingrese una descripción para el nuevo rol.',
            'rol.unique' => 'Este rol ya ha sido registrado, debe ingresar uno diferente.',
            'itemsPermisos.required' => 'Debe seleccionar al menos un permiso para este rol.'
          ]
        );

        //aqui guardo el rol nuevo
        $obj = new Roles();
        $resultado=$obj->guardar_rol($request);
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
