<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTitularesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('titulares', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('');
            $table->string('nombre')->comment('');
            $table->longText('fotografia')->nullable()->comment('');
            $table->string('colonia')->nullable()->comment('');
            $table->string('calle')->nullable()->comment('');
            $table->string('numero')->nullable()->comment('');
            $table->string('cp')->nullable()->comment('');
            $table->integer('localidad_id')->unsigned()->comment('localidad en la que vive el dueno de la poliza');
            $table->string('telefono')->nullable()->comment('');
            $table->string('email')->nullable()->comment('');
            $table->string('ocupacion')->nullable()->comment('');
            $table->string('edad')->nullable()->comment('');
            $table->string('latitud_casa')->nullable()->comment('latitud de donde ir a cobrar la poliza');
            $table->string('longitud_casa')->nullable()->comment('longitud de donde ir a cobrar la poliza');
            $table->string('nota')->nullable()->comment('algun comentario sobre el titular');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('titulares');
    }
}
