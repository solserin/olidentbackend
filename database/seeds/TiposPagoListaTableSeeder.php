<?php

use Illuminate\Database\Seeder;

class TiposPagoListaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data=[
            'Efectivo',
            'Cheque nominativo',
            'Transferencia electrónica de fondos',
            'Tarjeta de crédito',
            'Tarjeta de débito',
            'Monedero electrónico',
            'Dinero electrónico',
            'Vales de despensa',
            'Por definir',
        ];
        foreach($data as $dato){
          DB::table('formas_pago')->insert([
            'forma' => $dato
          ]);  
        }
    }
}
