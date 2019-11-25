<?php

namespace App\Http\Controllers;

use App\Empresas;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;

class EmpresasController extends ApiController
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //return base64_encode(file_get_contents(public_path('images/profile.png')));
        //le paso el 1 directo porque solo me interesa el numero 1
        return $this->showOne(Empresas::where('id', 1)->first());
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
          'logo' => 'required|image64:jpeg,jpg,png',
          'nombre' => 'required',
          'representante' => 'required',
          'email' => 'required|email',
          'telefono' => 'required',
          'calle' => 'required',
          'colonia' => 'required',
          'numero' => 'required',
          'cp' => 'required',
          'ciudad' => 'required',
          'descripcion' => 'required',
        ],
        [
          'required' => 'Este dato es obligatorio.',
          'email' => 'Debe ingresar un email en formato válido.',
          'image64'=>'El logotipo debe ser una imagen (png, jpg, jpeg).'
        ]
      );
     
      //aqui guardo el rol nuevo
      $obj = new Empresas();
      $resultado=$obj->update_empresa($request,$id);
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
}
