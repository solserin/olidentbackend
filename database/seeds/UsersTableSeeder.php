<?php

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //llena la tabla con 100 usuarios
       /* $roles = [1, 2, 3, 4, 5];
        for($x=0;$x<1000;$x++){
            DB::table('users')->insert([
                'name'=>str::random(25),
                'email'=>str::random(10).'@solserin.com',
                'password'=>Hash::make('secret'),
                'created_at'=>now(),
                'telefono'=>str::random(10),
                'roles_id'=>Arr::random($roles)
            ]);
        }*/

        $path =  public_path('images/profile.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

        DB::table('users')->insert([
            'name'=>'Kimberly Soler',
            'email'=>'kimberly@gmail.com',
            'password'=> Hash::make('secret'),
            'created_at'=>now(),
            'telefono'=>'por definir',
            'roles_id'=>1,
            'grupos_vendedores_id'=>3,
            'imagen'=>$base64
        ]);

        DB::table('users')->insert([
            'name'=>'GERARDO PONCE',
            'email'=>'gerardo@gmail.com',
            'password'=> Hash::make('secret'),
            'created_at'=>now(),
            'telefono'=>'por definir',
            'roles_id'=>4,
            'grupos_vendedores_id'=>2,
            'imagen'=>$base64
        ]);

        DB::table('users')->insert([
            'name'=>'CRISTOBAL BANUELOS',
            'email'=>'cristobal@gmail.com',
            'password'=> Hash::make('secret'),
            'created_at'=>now(),
            'telefono'=>'por definir',
            'roles_id'=>4,
            'grupos_vendedores_id'=>2,
            'imagen'=>$base64
        ]);

        DB::table('users')->insert([
            'name'=>'KAREN N',
            'email'=>'karen@gmail.com',
            'password'=> Hash::make('secret'),
            'created_at'=>now(),
            'telefono'=>'por definir',
            'roles_id'=>4,
            'grupos_vendedores_id'=>2,
            'imagen'=>$base64
        ]);

        DB::table('users')->insert([
            'name'=>'DIANA DIAZ',
            'email'=>'diana@gmail.com',
            'password'=> Hash::make('secret'),
            'created_at'=>now(),
            'telefono'=>'por definir',
            'roles_id'=>2,
            'grupos_vendedores_id'=>2,
            'imagen'=>$base64
        ]);

        DB::table('users')->insert([
            'name'=>'NORMA N',
            'email'=>'norma@gmail.com',
            'password'=> Hash::make('secret'),
            'created_at'=>now(),
            'telefono'=>'por definir',
            'roles_id'=>2,
            'grupos_vendedores_id'=>3,
            'imagen'=>$base64
        ]);

        DB::table('users')->insert([
            'name'=>'JOSUE BELTRAN',
            'email'=>'josue@gmail.com',
            'password'=> Hash::make('secret'),
            'created_at'=>now(),
            'telefono'=>'por definir',
            'roles_id'=>3,
            'grupos_vendedores_id'=>4,
            'imagen'=>$base64
        ]);

        DB::table('users')->insert([
            'name'=>'BRIAN N',
            'email'=>'brian@gmail.com',
            'password'=> Hash::make('secret'),
            'created_at'=>now(),
            'telefono'=>'por definir',
            'roles_id'=>3,
            'grupos_vendedores_id'=>4,
            'imagen'=>$base64
        ]);

        DB::table('users')->insert([
            'name'=>'MANUEL SOLER',
            'email'=>'manuel@gmail.com',
            'password'=> Hash::make('secret'),
            'created_at'=>now(),
            'telefono'=>'por definir',
            'roles_id'=>3,
            'grupos_vendedores_id'=>4,
            'imagen'=>$base64
        ]);
        //usando en seeds
        //factory('App\User', 10)->create();
    }
}
