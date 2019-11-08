<?php

namespace App\Http\Controllers\User;

use App\User;
use App\Roles;
use App\User as AppUser;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Api\ApiController;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth:api');
    } 
    
    public function index()
    {
        $key = Input::get('filter');
        //return $key;
        return $this->showAllPaginated(User::with('rol')->select(['imagen','created_at','id','name','email','roles_id','status'])->where('name', 'like', '%'.$key.'%')->orderBy('id','desc')->get());
    }
    //obtiene la lista de vendedores autorizados
    public function vendedores()
    {
        return $this->showAll(User::select(['id','name'])->where('grupos_vendedores_id', '>', '1')->orderBy('id','asc')->get());
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
          'nombre' => 'required',
          'usuario' => 'required|email|unique:users,email',
          'rol_id' => 'required',
          'estado' => 'required',
          'password' => 'required',
          'password_repetir' => 'required|same:password',
          'grupos_vendedores_id'=>'required'
        ],
        [
          'required' => 'Este dato es obligatorio.',
          'email' => 'Debe ingresar un email',
          'unique' => 'Ya existe un usuario con este email, ingrese uno diferente.',
          'same' => 'Las contraseñas ingresadas no coinciden.'
        ]
      );
     
      //aqui guardo el rol nuevo
      $obj = new User();
      $resultado=$obj->guardar_usuario($request);
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
        return $this->showOne(User::select('id','name','password','email','telefono','roles_id','status','grupos_vendedores_id')->where('id', $id)->first());
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
                'usuario_id' => 'required', 
                'nombre' => 'required',
                'usuario' => ['email','required',Rule::unique('users','email')->ignore($id)],
                'rol_id' => 'required',
                'estado' => 'required',
                'grupos_vendedores_id'=>'required'
            ],
            [
                'required' => 'Este dato es obligatorio.',
                'email' => 'Debe ingresar un email',
                'unique' => 'Ya existe un usuario con este email, ingrese uno diferente.'
            ]
        );
        //aqui actualizo el usuario
        $obj = new User();
        $resultado=$obj->update_usuario($request,$id);
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
           //se puede eliminar
           $obj = new User();
           $resultado=$obj->delete_user($id);
           return $resultado;
    }



    //regresa los datos para crear la intefaz de modulos del usuario
    public function loadPerfil($id_user=0)
    {
        $auth = new Roles();
        return $this->showAll($auth->roles_modulos_permisos($id_user));
    }

     //regresa de un usuario por email
     public function getUserByEmail($email='')
     {
        return $this->showOne(User::where('status', 1)->where('email',$email)->first(['id','email','imagen','name','telefono','updated_at','roles_id']));
     }



     //aqui el usuario modifca su propio perfil
     public function update_perfil(Request $request,$id)
     {
        request()->validate(
             [
                 'imagen' => 'required|image64:jpeg,jpg,png',
                 'id' => 'required', 
                 'nombre' => 'required',
                 'password'=>'sometimes|same:password_repetir',
                 'password_repetir'=>'sometimes|same:password',
                 'verificar_usuario' => 'required',
             ],
             [
                'image64'=>'El logotipo debe ser una imagen (png, jpg, jpeg).',
                'required' => 'Este dato es obligatorio.',
                'same' => 'Las nuevas contraseñas no coinciden',
                'image64'=>'El logotipo debe ser una imagen (png, jpg, jpeg).'
            ]
         );
         //aqui actualizo el usuario
         $obj = new User();
         $resultado=$obj->update_perfil($request,$id);
         return $resultado;
     }
}
