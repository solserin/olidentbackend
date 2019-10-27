<?php

use Illuminate\Database\Seeder;

class TiposVentaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data=[
            'Afiliación',
            'Renovación',
        ];
        foreach($data as $dato){
          DB::table('tipos_venta')->insert([
            'tipo' => $dato
          ]);  
        }
    }
}
