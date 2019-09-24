<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens,Notifiable;

    const USUARIO_ACTIVO="1";
    const USUARIO_NO_ACTIVO="0";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    

    public function estaActivo(){
        return $this->status==User::USUARIO_ACTIVO;
    }



    //relaciones con otras tablas

    //un usuario puede tener solo un  rol (roles)
    public function rol(){
        return $this->belongsTo('App\Roles','roles_id','id');
    }

    //con esta funcion reviso que el usuario exista y este activo
    public function findForPassport($username)
    {
        //aqui checo que el usuario este activo
        return $this->where(['email'=>$username,'status'=>1])->first();
    }


    //aqui guardo los datos del nuevo usuario
    public function guardar_usuario(Request $request){
        try {
            date_default_timezone_set('America/Mazatlan');
            //return $request->estado;
            $user_id=DB::table('users')->insertGetId(
                [
                    'name' =>$request->nombre,
                    'email' =>$request->usuario,
                    'password' =>Hash::make($request->password),
                    'telefono' =>$request->telefono,
                    'roles_id' =>$request->rol_id,
                    'created_at' =>Carbon::now()->format('Y-m-d H:i:s'),
                    'status'=>$request->estado
                ]
            );
            return $user_id;
        } catch (\Throwable $th) {
            return 0;
        }
    }

    //actualizo un usuario
    public function update_usuario(Request $request,$id){
        try {
            DB::table('users')->where('id',$request->usuario_id)->update(
                [
                    'name' =>$request->nombre,
                    'email' =>$request->usuario,
                    'telefono' =>$request->telefono,
                    'roles_id' =>$request->rol_id,
                    'status'=>$request->estado
                ]
            );
            return $id;
        } catch (\Throwable $th) {
            return 0;
        }
    }

     //aqui elimino el rol- logicamente
     public function delete_user($id){
        try {
            DB::table('users')->where('id', '=', $id)->update(['status'=>0]);
            return $id;
        } catch (\Throwable $th) {
            return -1;
        }
    }
}