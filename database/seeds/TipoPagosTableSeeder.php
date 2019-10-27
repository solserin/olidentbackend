<?php

use Illuminate\Database\Seeder;

class TipoPagosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data=[
            'Fijo',
            'Previa Valoración/Cita Previa',
        ];
        foreach($data as $dato){
          DB::table('tipo_precios')->insert([
            'tipo' => $dato
          ]);  
        }
    }
}
