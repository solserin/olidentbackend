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
                'descripcion'=>'Grupo 1 de vendedores',
            ],
            [
                'grupo'=>'Venta de mostrador',
                'descripcion'=>'Usuarios que pueden vender en recepción',
            ],
            [
                'grupo'=>'Venta en cobranza',
                'descripcion'=>'Usuarios que pueden vender durante cobranza',
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
