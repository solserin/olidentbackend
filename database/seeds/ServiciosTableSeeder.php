<?php

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;

class ServiciosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data=[
            //tipo diagnostico
            [
                'servicio'=>'consultas',
                'precio_normal'=>250,
                'descuento_poliza'=>100,
                'tipo_precio_id'=>1,
                'tipo_id'=>1
            ],
            [
                'servicio'=>'rx. en diente individual',
                'precio_normal'=>160,
                'descuento_poliza'=>50,
                'tipo_precio_id'=>1,
                'tipo_id'=>1
            ],
            [
                'servicio'=>'diagnostico completo en rx',
                'precio_normal'=>1300,
                'descuento_poliza'=>50,
                'tipo_precio_id'=>1,
                'tipo_id'=>1
            ], 
            //tipo higiene
            [
                'servicio'=>'aplicacion topica de fluor',
                'precio_normal'=>280,
                'descuento_poliza'=>100,
                'tipo_precio_id'=>1,
                'tipo_id'=>2
            ],
            [
                'servicio'=>'limpieza completa profilaxis',
                'precio_normal'=>400,
                'descuento_poliza'=>100,
                'tipo_precio_id'=>1,
                'tipo_id'=>2
            ],
            [
                'servicio'=>'tratamiento peridontal en 4 citas',
                'precio_normal'=>4800,
                'descuento_poliza'=>50,
                'tipo_precio_id'=>1,
                'tipo_id'=>2
            ], 
            [
                'servicio'=>'gingivioplastia (minimo sangrado)',
                'precio_normal'=>1000,
                'descuento_poliza'=>50,
                'tipo_precio_id'=>1,
                'tipo_id'=>2
            ],
            //tipo estetica
            [
                'servicio'=>'obstruccion temporal (2 meses)',
                'precio_normal'=>400,
                'descuento_poliza'=>50,
                'tipo_precio_id'=>1,
                'tipo_id'=>3
            ],
            [
                'servicio'=>'amalgamas',
                'precio_normal'=>520,
                'descuento_poliza'=>50,
                'tipo_precio_id'=>1,
                'tipo_id'=>3
            ],
            [
                'servicio'=>'resina estetica en diente',
                'precio_normal'=>630,
                'descuento_poliza'=>50,
                'tipo_precio_id'=>1,
                'tipo_id'=>3
            ], 
            [
                'servicio'=>'resina estetica en molar',
                'precio_normal'=>520,
                'descuento_poliza'=>50,
                'tipo_precio_id'=>1,
                'tipo_id'=>3
            ],
            [
                'servicio'=>'resina en molar, varias superficies',
                'precio_normal'=>560,
                'descuento_poliza'=>50,
                'tipo_precio_id'=>1,
                'tipo_id'=>3
            ],
            [
                'servicio'=>'resina e-max',
                'precio_normal'=>7000,
                'descuento_poliza'=>50,
                'tipo_precio_id'=>1,
                'tipo_id'=>3
            ],  
            [
                'servicio'=>'pomero de vidrio con lberacion de fluor',
                'precio_normal'=>400,
                'descuento_poliza'=>50,
                'tipo_precio_id'=>1,
                'tipo_id'=>3
            ],
            [
                'servicio'=>'blanqueamiento en una cita',
                'precio_normal'=>2500,
                'descuento_poliza'=>50,
                'tipo_precio_id'=>1,
                'tipo_id'=>3
            ],
            [
                'servicio'=>'blanqueamiento casero en 7 dias',
                'precio_normal'=>2500,
                'descuento_poliza'=>50,
                'tipo_precio_id'=>1,
                'tipo_id'=>3
            ],      
        ];
        foreach($data as $dato){
        DB::table('servicios')->insert([
            'servicio' => strtoupper($dato['servicio']),
            'precio_normal' => $dato['precio_normal'],
            'descuento_poliza' => $dato['descuento_poliza'],
            'tipo_precio_id' => $dato['tipo_precio_id'],
            'tipo_id' => $dato['tipo_id'],
            'created_at' =>Carbon::now()->format('Y-m-d H:i:s'),
        ]);  
        }
    }
}
