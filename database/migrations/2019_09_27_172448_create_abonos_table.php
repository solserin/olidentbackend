<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAbonosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('abonos', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('');
            $table->integer('polizas_id')->unsigned()->comment('Numero de la poliza');
            $table->dateTime('fecha_abono')->comment('fecha en que se recibio el abono');
            $table->integer('formas_pago_id')->unsigned()->comment('forma de pago usada');
            $table->double('cantidad')->nullable()->comment('cantidad del abono');
            $table->integer('cobrador_id')->unsigned()->comment('id del cobrador');
            $table->integer('usuario_capturo_id')->unsigned()->comment('id del usuario que registro la venta en el sistema');
            $table->string('latitud_cobro')->nullable()->comment('latitud de donde se hizo el cobro la poliza');
            $table->string('longitud_cobro')->nullable()->comment('longitud de donde se hizo el cobro la poliza');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('abonos');
    }
}
