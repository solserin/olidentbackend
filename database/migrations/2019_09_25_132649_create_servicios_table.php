<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiciosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servicios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('servicio',75)->unique();
            $table->string('descripcion')->nullable();
            $table->timestamps();
            $table->double('precio_normal', 8, 2);
            $table->integer('descuento_poliza')->comment('captura el porcentaje de descuento del precio normal');
            $table->integer('tipo_precio_id')->unsigned()->comment('distingue si el precio es directo o si ocupa alguna previa valoracion');
            $table->integer('tipo_id')->unsigned();
            $table->integer('status')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('servicios');
    }
}
