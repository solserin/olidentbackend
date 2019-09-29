<?php

use Illuminate\Database\Seeder;

class RutasTableSeeder extends Seeder
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
                'ruta'=>'Ruta 1',
                'descripcion'=>'Villa union y rancherias',
                'cobrador_id'=>'2'
            ],
            [
                'ruta'=>'Ruta 2',
                'descripcion'=>'Rosario',
                'cobrador_id'=>'4'
            ],
            [
                'ruta'=>'Ruta 3',
                'descripcion'=>'Recodo villa union',
                'cobrador_id'=>'6'
            ]
        ];
        foreach($data as $dato){
          DB::table('rutas')->insert([
            'ruta' => $dato['ruta'],
            'descripcion' => $dato['descripcion'],
            'cobrador_id' => $dato['cobrador_id'],
          ]);  
        }
    }
}
