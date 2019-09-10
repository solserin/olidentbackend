<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermisosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data=[
            'Consultar',
            'Agregar',
            'Modificar',
            'Eliminar'
        ];
        foreach($data as $dato){
          DB::table('permisos')->insert([
            'permiso' => $dato
          ]);  
        }
    }
}
