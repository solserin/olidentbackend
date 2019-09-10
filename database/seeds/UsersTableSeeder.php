<?php

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data=[
            [
                'name'=>str::random(25),
                'email'=>'hcruz@solserin.com',
                'password'=>bcrypt('secret'),
                'created_at'=>now(),
                'telefono'=>str::random(10),
                'roles_id'=>1
            ]
        ];
        foreach($data as $dato){
            DB::table('users')->insert([
                'name' => $dato['name'],
                'email' => $dato['email'],
                'password' => $dato['password'],
                'telefono'=> $dato['telefono'],
                'roles_id'=> $dato['roles_id'],
            ]);
        }
    }
}
