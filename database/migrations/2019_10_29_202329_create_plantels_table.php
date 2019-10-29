<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlantelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plantels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre');
            $table->string('excelencia')->nullable();
            $table->string('correo_electronico')->nullable();
            $table->string('pagina_web')->nullable();
            $table->unsignedInteger('institucion_id');
            $table->foreign('institucion_id')->references('id')->on('institucions');
            $table->unsignedInteger('direccion_id');
            $table->foreign('direccion_id')->references('id')->on('direccions');
            $table->unsignedInteger('director_id')->nullable();;
            $table->foreign('director_id')->references('id')->on('directors');
            $table->unsignedInteger('telefono_id');
            $table->foreign('telefono_id')->references('id')->on('telefonos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plantels');
    }
}
