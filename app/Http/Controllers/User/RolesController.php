<?php

namespace App\Http\Controllers\User;

use App\Roles;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Api\ApiController;

class RolesController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $key = Input::get('filter');
        //return $key;
        return $this->showAllPaginated(Roles::select(['id','rol'])->withCount('usuarios')->where('rol', 'like', '%'.$key.'%')->where('status', '=','1')->orderBy('id','desc')->get());
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
            'rol.required' => 'Ingrese una descripción para el rol.',
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
        //aqui obtengo los ids de los permisos que le corresponden a cada rol para modificar un rol
        $obj = new Roles();
        $permisos_ids=$obj->get_permisos_de_rol($id);
        return $this->showAll($permisos_ids);
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
              'id_rol' => 'required', 
              'rol' => ['required', Rule::unique('roles')->ignore($id)],
              'itemsPermisos' => 'required'
            ],
            [
              'rol.required' => 'Ingrese una descripción para el rol.',
              'rol.unique' => 'Este rol ya ha sido registrado, debe ingresar uno diferente.',
              'itemsPermisos.required' => 'Debe seleccionar al menos un permiso para este rol.'
            ]
          );
          //aqui guardo el rol nuevo
          $obj = new Roles();
          $resultado=$obj->update_rol($request,$id);
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
        $res=Roles::withCount('usuarios')->where('id', $id)->first();
        if($res['usuarios_count']>0){
          //tiene usuarios asociados y no se puede eliminar
          return -1;
        }else{
          //se puede eliminar
          $obj = new Roles();
          $resultado=$obj->delete_rol($id);
          return $resultado;
        }
        return $res;
    }
}
