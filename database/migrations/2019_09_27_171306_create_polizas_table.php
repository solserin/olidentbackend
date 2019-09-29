<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePolizasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('polizas', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('');
            $table->integer('num_poliza')->comment('numero de la poliza');
            $table->dateTime('fecha_afiliacion')->nullable()->comment('fecha en que se afilio la persona a la clinica');
            $table->dateTime('fecha_compra')->nullable()->comment('fecha en que se hizo la compra de la poliza o la renovacion');
            $table->dateTime('fecha_vencimiento')->nullable()->comment('fecha en que se hizo la compra de la poliza o la renovacion');
            $table->integer('usuario_capturo_id')->unsigned()->comment('localidad en la que vive el dueno de la poliza');
            $table->double('total')->nullable()->comment('');
            $table->double('abonado')->nullable()->comment('');
            $table->double('restante')->nullable()->comment('');
            $table->integer('titulares_id')->unsigned()->comment('Titular de la poliza');
            $table->integer('tipo_polizas_id')->unsigned()->comment('Tipo de poliza');
            $table->integer('vendedor_id')->unsigned()->comment('id del usuario que vendio la poliza');
            $table->integer('tipos_venta_id')->unsigned()->comment('Tipo de venta, nueva o renovacion');
            $table->smallInteger('status')->default('1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('polizas');
    }
}
