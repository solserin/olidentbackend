<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesPermisosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data=[
            //empresa
            [
                'modulos_id'=>1,
                'permisos_id'=>1,
                'roles_id'=>1,
            ],
            [
                'modulos_id'=>1,
                'permisos_id'=>2,
                'roles_id'=>1,
            ],
            [
                'modulos_id'=>1,
                'permisos_id'=>3,
                'roles_id'=>1,
            ],
            [
                'modulos_id'=>2,
                'permisos_id'=>4,
                'roles_id'=>1,
            ],
            //usuarios
            [
                'modulos_id'=>2,
                'permisos_id'=>1,
                'roles_id'=>1,
            ],
            [
                'modulos_id'=>2,
                'permisos_id'=>2,
                'roles_id'=>1,
            ],
            [
                'modulos_id'=>2,
                'permisos_id'=>3,
                'roles_id'=>1,
            ],
            [
                'modulos_id'=>2,
                'permisos_id'=>4,
                'roles_id'=>1,
            ],

            [
                'modulos_id'=>3,
                'permisos_id'=>1,
                'roles_id'=>1,
            ],
            [
                'modulos_id'=>3,
                'permisos_id'=>2,
                'roles_id'=>1,
            ],
            [
                'modulos_id'=>3,
                'permisos_id'=>3,
                'roles_id'=>1,
            ],
            [
                'modulos_id'=>3,
                'permisos_id'=>4,
                'roles_id'=>1,
            ],

            //servicios
            [
                'modulos_id'=>4,
                'permisos_id'=>1,
                'roles_id'=>1,
            ],
            [
                'modulos_id'=>4,
                'permisos_id'=>2,
                'roles_id'=>1,
            ],
            [
                'modulos_id'=>4,
                'permisos_id'=>3,
                'roles_id'=>1,
            ],
            [
                'modulos_id'=>4,
                'permisos_id'=>4,
                'roles_id'=>1,
            ],
           
        ];
        foreach($data as $dato){
          DB::table('roles_permisos')->insert([
            'modulos_id' => $dato['modulos_id'],
            'permisos_id' => $dato['permisos_id'],
            'roles_id' => $dato['roles_id']
          ]);  
        }
    }
}
