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
            $table->integer('num_poliza')->comment('numero unico de la poliza');
            $table->date('fecha_afiliacion')->nullable()->comment('fecha en que se afilio la persona a la clinica');
            $table->integer('usuario_capturo_id')->unsigned()->comment('id del usuario que guardo la poliza');
            $table->integer('rutas_id')->unsigned()->comment('id de la ruta a la que se asignara cobro');
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
