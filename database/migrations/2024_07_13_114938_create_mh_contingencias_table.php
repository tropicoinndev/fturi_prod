<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mh_contingencias', function (Blueprint $table) {
            $table->id();
            $table->string("codigo_generacion")->nullable();
            $table->dateTime("fecha_procesamiento")->nullable();
            $table->string("sello_recibido")->nullable();
            $table->boolean("resuelto")->default(false);
            $table->boolean("mh")->default(false);
            $table->date("fecha_inicio")->nullable();
            $table->date("fecha_fin")->nullable();
            $table->time("hora_inicio")->nullable();
            $table->time("hora_fin")->nullable();
            $table->integer("tipo_contingencia")->nullable();
            $table->string("motivoContingencia")->nullable();
            $table->text("json")->nullable();
            $table->text("response")->nullable();
            $table->foreignId("sucursales_id")->constrained('sucursales');
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
        Schema::dropIfExists('mh_contingencias');
    }
};
