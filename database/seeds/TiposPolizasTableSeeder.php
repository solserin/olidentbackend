<?php

use Illuminate\Database\Seeder;

class TiposPolizasTableSeeder extends Seeder
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
                'tipo'=>'Individual',
                'descripcion'=>'Para una sola persona',
                'precio'=>398,
                'numero_beneficiarios'=>1,
                'minimo_abono'=>70,
                'semanas_abono'=>9
            ],
            [
                'tipo'=>'Familiar',
                'descripcion'=>'Para un titular y cuatro beneficiarios',
                'precio'=>750,
                'numero_beneficiarios'=>4,
                'minimo_abono'=>70,
                'semanas_abono'=>9
            ],
            [
                'tipo'=>'Familiar Plus',
                'descripcion'=>'Para un titular y cuatro beneficarios',
                'precio'=>998,
                'numero_beneficiarios'=>4,
                'minimo_abono'=>70,
                'semanas_abono'=>9
            ]
        ];
        foreach($data as $dato){
          DB::table('tipo_polizas')->insert([
            'tipo' => $dato['tipo'],
            'descripcion' => $dato['descripcion'],
            'precio' => $dato['precio'],
            'numero_beneficiarios' => $dato['numero_beneficiarios'],
            'minimo_abono' => $dato['minimo_abono'],
            'semanas_abono' => $dato['semanas_abono'],
          ]);  
        }
    }
}
