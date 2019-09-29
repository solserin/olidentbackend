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
                'grupo'=>'Empresa',
                'icon'=>'icon-wrench'
            ],
            [
                'grupo'=>'Usuarios',
                'icon'=>'icon-people'
            ],
            [
                'grupo'=>'Catálogos',
                'icon'=>'icon-book-open'
            ],
            [
                'grupo'=>'Pólizas',
                'icon'=>'icon-paper-clip'
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