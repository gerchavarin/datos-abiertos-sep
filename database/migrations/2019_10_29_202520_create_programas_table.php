<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgramasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('programas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre');
            $table->string('llave');
            $table->string('acuerdo');
            $table->string('fecha_otorgamiento_rvoe');
            $table->string('fecha_retiro_rvoe')->nullable();
            $table->unsignedInteger('plantel_id');
            $table->foreign('plantel_id')->references('id')->on('plantels');
            $table->unsignedInteger('nivel_id');
            $table->foreign('nivel_id')->references('id')->on('nivels');
            $table->unsignedInteger('area_id');
            $table->foreign('area_id')->references('id')->on('areas');
            $table->unsignedInteger('modalidad_id');
            $table->foreign('modalidad_id')->references('id')->on('modalidads');
            $table->unsignedInteger('estatus_id');
            $table->foreign('estatus_id')->references('id')->on('estatuses');
            $table->unsignedInteger('motivo_retiro_id');
            $table->foreign('motivo_retiro_id')->references('id')->on('motivo_retiros');
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
        Schema::dropIfExists('programas');
    }
}
