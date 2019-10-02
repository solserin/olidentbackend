<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('');
            $table->date('fecha_registro')->comment('fecha en que se guardo la venta');
            $table->date('fecha_venta')->comment('fecha en que se hizo la venta');
            $table->date('fecha_vencimiento')->comment('fecha en que se vence la poliza');
            $table->integer('tipos_venta_id')->unsigned()->comment('relacion con el tipo de venta');
            $table->integer('vendedor_id')->unsigned()->comment('vendedor que hizo la venta');
            $table->integer('polizas_id')->unsigned()->comment('numero de la poliza');
            $table->integer('tipo_polizas_id')->unsigned()->comment('relacion con el tipo de poliza');
            $table->integer('num_beneficiarios')->nullable()->comment('numero de beneficiarios de la poliza');
            $table->double('total')->nullable()->comment('total de la venta');
            $table->double('abonado')->nullable()->comment('total pagado de la venta');
            $table->double('restante')->nullable()->comment('total restante de la venta');
            $table->double('comision_vendedor')->nullable()->comment('total de la comision por venta');
            $table->integer('status')->default(1)->comment('');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ventas');
    }
}
