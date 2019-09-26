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

        //usando en seeds
        factory('App\User', 10)->create();
    }
}
