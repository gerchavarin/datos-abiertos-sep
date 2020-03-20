<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusInstitucionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('status_institucions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('institucion_id');
            $table->integer('status_id');
            $table->integer('sostenimiento_id');
            $table->string('nombre_institucion');
            $table->integer('entidad_id');
            $table->string('descripcion_entidad');
            $table->string('descripcion_status');
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
        Schema::dropIfExists('status_institucions');
    }
}