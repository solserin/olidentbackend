<?php

use Illuminate\Database\Seeder;

class GruposVendedoresTableSeeder extends Seeder
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
                'grupo'=>'Sin permiso a vender',
                'descripcion'=>'Este grupo es para aquellos que no tienen autorizado vender',
            ],
            [
                'grupo'=>'Grupo 1',
                'descripcion'=>'Grupo 1 des',
            ],
            [
                'grupo'=>'Grupo 2',
                'descripcion'=>'Grupo 2 des',
            ],
            [
                'grupo'=>'Grupo 3',
                'descripcion'=>'Grupo 3 des',
            ],
            [
                'grupo'=>'Grupo 4',
                'descripcion'=>'Grupo 4 des',
            ]
        ];
        foreach($data as $dato){
          DB::table('grupos_vendedores')->insert([
            'grupo' => $dato['grupo'],
            'descripcion' => $dato['descripcion'],
          ]);  
        }
    }
}
