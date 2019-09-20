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
                'url'=>'/usuarios/roles',
                'icon'=>'icon-speedometer',
                'grupos_id'=>1
            ],
            [
                'modulo'=>'Usuarios',
                'name'=>'Usuarios',
                'url'=>'/usuarios',
                'icon'=>'icon-speedometer',
                'grupos_id'=>1
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
