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
            //usuarios
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
            //servicios
            [
                'modulo'=>'Servicios',
                'name'=>'Servicios',
                'url'=>'/catalogos/servicios',
                'icon'=>'icon-basket-loaded',
                'grupos_id'=>3
            ],
            [
                'modulo'=>'Vendedores',
                'name'=>'Vendedores',
                'url'=>'/catalogos/vendedores',
                'icon'=>'icon-people',
                'grupos_id'=>3
            ],
            // modulos de polizas
             //servicios
             [
                'modulo'=>'Control de pólizas',
                'name'=>'Polizas',
                'url'=>'/polizas',
                'icon'=>'icon-note',
                'grupos_id'=>4
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
