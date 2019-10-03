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
            $table->date('fecha_abono')->comment('fecha en que se hizo el abono');
            $table->date('fecha_registro')->comment('fecha en que se guardo el abono');
            $table->integer('formas_pago_id')->unsigned()->comment('relacion con el tipo de pago');
            $table->double('cantidad')->nullable()->comment('total pagado de la venta');
            $table->integer('cobrador_id')->unsigned()->comment('relacion del cobrador');
            $table->integer('usuario_capturo_id')->unsigned()->comment('relacion con el usuario');
            $table->string('latitud_cobro')->nullable()->comment('latitud de donde se cobro la poliza');
            $table->string('longitud_cobro')->nullable()->comment('longitud de donde se cobro la poliza');
            $table->integer('ventas_id')->unsigned()->comment('relacion con la venta');
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
