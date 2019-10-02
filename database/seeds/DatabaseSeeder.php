<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $this->call([
           RolesTableSeeder::class,
           GruposTableSeeder::class,
           ModulosTableSeeder::class,
           PermisosTableSeeder::class,
           RolesPermisosTableSeeder::class,
           UsersTableSeeder::class,
           EmpresasTableSeeder::class,
           TipoPagosTableSeeder::class,
           TipoServiciosTableSeeder::class,
           ServiciosTableSeeder::class,
           TiposPagoListaTableSeeder::class,
           TiposPolizasTableSeeder::class,
           TiposVentaTableSeeder::class,
           GruposVendedoresTableSeeder::class,
           RutasTableSeeder::class,
           TiposBeneficiariosTableSeeder::class,
       ]);
    }
}
?>