<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TiposBeneficiariosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tipos=[
            'Titular',
            'Beneficiario'
        ];
        foreach($tipos as $tipo){
          DB::table('tipo_beneficiarios')->insert([
            'tipo' => $tipo
          ]);  
        }
    }
}
