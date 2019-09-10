<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles=[
            'Administrador',
            'Recepcionista',
            'Cobrador',
            'Dentista',
            'Vendedor'
        ];
        foreach($roles as $rol){
          DB::table('roles')->insert([
            'rol' => $rol
          ]);  
        }
    }
}
