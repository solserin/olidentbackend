<?php

namespace App;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\CustomResetPasswordNotification;
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
            $path =  public_path('images/profile.png');
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

            $user_id=DB::table('users')->insertGetId(
                [
                    'name' =>strtoupper($request->nombre),
                    'email' =>$request->usuario,
                    'password' =>Hash::make($request->password),
                    'telefono' =>strtoupper($request->telefono),
                    'imagen'=> $base64,
                    'roles_id' =>$request->rol_id,
                    'created_at' =>Carbon::now()->format('Y-m-d H:i:s'),
                    'status'=>$request->estado,
                    'grupos_vendedores_id'=>$request->grupos_vendedores_id,
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
                    'name' =>strtoupper($request->nombre),
                    'email' =>$request->usuario,
                    'telefono' =>strtoupper($request->telefono),
                    'roles_id' =>$request->rol_id,
                    'status'=>$request->estado,
                    'updated_at' =>Carbon::now()->format('Y-m-d H:i:s'),
                    'grupos_vendedores_id'=>$request->grupos_vendedores_id,
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



      //actualizo el perfil del usuarios
      public function update_perfil(Request $request,$id){
        try {
            //primero confirmamos que el usuario es el dueño de la cuenta
            $user_por_id=User::select('id','email','imagen','name','telefono','updated_at','password')->where('id',$id)->first();
            if (Hash::check($request->verificar_usuario,$user_por_id->password)) {
                //la contraseña que ingreso es correcta y podemos continuar
                if($request->password && $request->password_repetir){
                    //aqui modificamos todos los datos incluido la contraseña
                    DB::table('users')->where('id',$id)->update(
                        [
                            'name' => strtoupper($request->nombre),
                            'telefono' => strtoupper($request->telefono),
                            'imagen' => $request->imagen,
                            'updated_at' =>Carbon::now()->format('Y-m-d H:i:s'),
                            'password'=>Hash::make($request->password)
                        ]
                    );
                }else{
                    //solo modifico nombre, telefono e imagen
                     //aqui modificamos todos los datos incluido la contraseña
                     DB::table('users')->where('id',$id)->update(
                        [
                            'name' => strtoupper($request->nombre),
                            'telefono' => strtoupper($request->telefono),
                            'imagen' => $request->imagen,
                            'updated_at' =>Carbon::now()->format('Y-m-d H:i:s'),
                        ]
                    );
                }
                return User::select('id','email','imagen','name','telefono','updated_at','password')->where('id',$id)->first();
            }else{
                //la contraseña de verificacion de cuenta no es correcta
                return -1;
            }
        } catch (\Throwable $th) {
            return 0;
        }
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPasswordNotification($token));
    }
}