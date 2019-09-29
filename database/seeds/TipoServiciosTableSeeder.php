<?php

use Illuminate\Database\Seeder;

class TipoServiciosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data=[
               'diagnóstico',
               'Higiene y salud bucal',
               'estetica y restauracion dental',
               'cirugia dental',
               'endodoncia',
               'ortodoncia',
               'prostodoncia fija',
               'prostodoncia total',
               'prostodoncia removible',
               'odontopediatria'
        ];
        foreach($data as $dato){
          DB::table('tipo_servicios')->insert([
            'tipo' => strtoupper($dato)
          ]);  
        }
    }
}
