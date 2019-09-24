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
                'modulo'=>'Información',
                'name'=>'empresa',
                'url'=>'/empresa',
                'icon'=>'icon-home',
                'grupos_id'=>1
            ],
            [
                'modulo'=>'Roles de Usuarios',
                'name'=>'Roles',
                'url'=>'/usuarios/roles',
                'icon'=>'icon-people',
                'grupos_id'=>2
            ],
            [
                'modulo'=>'Usuarios',
                'name'=>'Usuarios',
                'url'=>'/usuarios',
                'icon'=>'icon-user',
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
