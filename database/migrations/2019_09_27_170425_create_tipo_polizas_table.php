<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTipoPolizasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_polizas', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('');
            $table->string('tipo')->comment('');
            $table->string('descripcion')->nullable()->comment('');
            $table->double('precio')->comment('valor de la poliza');
            $table->string('numero_beneficiarios')->comment('cantidad de personas que pueden acceder a la poliza');
            $table->double('minimo_abono')->comment('minima cantidad por abono');
            $table->integer('semanas_abono')->unsigned()->comment('numero de semanas a pagar la poliza');
            $table->smallInteger('duracion')->default('1');
            $table->integer('status')->default('1')->comment('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipo_polizas');
    }
}
