<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GruposTableSeeder extends Seeder
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
                'grupo'=>'Usuarios',
                'icon'=>'icon-speedometer'
            ],
            [
                'grupo'=>'Pólizas',
                'icon'=>'icon-speedometer'
            ]
        ];
        foreach($data as $dato){
          DB::table('grupos')->insert([
            'grupo' => $dato['grupo'],
            'icon' => $dato['icon']
          ]);  
        }
    }
}