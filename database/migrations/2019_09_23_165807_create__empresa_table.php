<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpresaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre');
            $table->string('representante');
            $table->string('email')->unique();
            $table->longText('logo')->nullable();
            $table->string('telefono')->nullable();
            $table->string('calle')->nullable();
            $table->string('colonia')->nullable();
            $table->string('numero')->nullable();
            $table->string('descripcion')->nullable();
            $table->string('ciudad')->nullable();
            $table->string('cp')->nullable();
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
        Schema::dropIfExists('empresas');
    }
}
