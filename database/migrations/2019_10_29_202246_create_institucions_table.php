<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstitucionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('institucions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre');
            $table->string('razon_social')->nullable();
            $table->string('autorizada_equivalencias')->nullable();
            $table->string('fecha_aut_revalidacion_equivalencia')->nullable();
            $table->string('fecha_rev_revalidacion_equivalencia')->nullable();
            $table->unsignedInteger('grupo_id');
            $table->foreign('grupo_id')->references('id')->on('grupos');
            $table->unsignedInteger('tipo_rvoe_id');
            $table->foreign('tipo_rvoe_id')->references('id')->on('tipo_rvoes');
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
        Schema::dropIfExists('institucions');
    }
}
