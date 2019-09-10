<?php

use Illuminate\Database\Seeder;

class ModulosTableSeeder extends Seeder
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
                'modulo'=>'Roles de Usuarios',
                'name'=>'Roles',
                'url'=>'Roles de Usuarios',
                'icon'=>'icon-speedometer',
                'grupos_id'=>2
            ],
            [
                'modulo'=>'Usuarios',
                'name'=>'Usuarios',
                'url'=>'/',
                'icon'=>'icon-speedometer',
                'grupos_id'=>2
            ],
        ];
        foreach($data as $dato){
          DB::table('modulos')->insert([
            'modulo' => $dato['modulo'],
            'name' => $dato['name'],
            'url' => $dato['url'],
            'icon' => $dato['icon'],
            'grupos_id' => $dato['grupos_id']
          ]);  
        }
    }
}
