<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBeneficiariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('beneficiarios', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('');
            $table->string('nombre')->comment('');
            $table->string('edad')->nullable()->comment('');
            $table->string('telefono')->nullable()->comment('');
            $table->string('email')->nullable()->comment('');
            $table->longText('fotografia')->nullable()->comment('');
            $table->integer('polizas_id')->unsigned()->comment('indica a que poliza pertenece');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('beneficiarios');
    }
}
