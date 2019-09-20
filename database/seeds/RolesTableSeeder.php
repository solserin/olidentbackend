<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*$roles=[
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
      */
        
        for($x=0;$x<500;$x++){
            DB::table('roles')->insert([
                'rol'=>str::random(15),
            ]);
        }


    }
}
