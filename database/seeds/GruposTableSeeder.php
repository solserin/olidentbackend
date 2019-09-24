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
                'grupo'=>'Pólizas',
                'icon'=>'icon-folder-alt'
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