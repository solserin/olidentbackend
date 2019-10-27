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
                'ruta'=>'Ruta 2 - Rosario',
                'descripcion'=>'Rosario',
                'cobrador_id'=>'7'
            ],
            [
                'ruta'=>'Ruta 1 - Villa unión',
                'descripcion'=>'Localidades de villaunion	',
                'cobrador_id'=>'8'
            ],
            [
                'ruta'=>'Ruta 3 - Villa union',
                'descripcion'=>'Villa unión',
                'cobrador_id'=>'9'
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
