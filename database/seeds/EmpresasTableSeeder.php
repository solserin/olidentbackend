<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmpresasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('empresas')->insert([
            'nombre' => 'Clínica Dental Oli Dent',
            'representante' => 'Dra. Cynthia Oliva López Martínez - Cirujano Dentista - U.A.S',
            'email' => 'olident.salud@gmail.com',
            'logo' => '',
            'telefono' => '6691 930497',
            'calle' => 'Francisco I Madero',
            'numero'=>'407',
            'colonia' => '',
            'descripcion' =>'Entre 5 de Febrero y 20 de Noviembre.',
            'ciudad' => ' Villa Unión Mazatlan',
            'cp' => '',
          ]);  
    }
}
