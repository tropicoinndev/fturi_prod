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
        Schema::create('dtes', function (Blueprint $table) {
            $table->id();
            $table->string("correlativo");
            $table->string("codigo_generacion")->nullable();
            $table->string("sello_recibido")->nullable();
            $table->string("estado")->nullable();
            $table->string("observaciones")->nullable();
            $table->dateTime("fecha_procesamiento")->nullable();
            $table->boolean("error")->default(false);
            $table->integer("enviado")->default(0);
            $table->integer("tipo_dte");
            $table->foreignId("sucursales_id")->constrained("sucursales");
            $table->foreignId("contingencias_id")->nullable()->constrained("contingencias");
            $table->foreignId("users_id")->constrained("users");
            $table->foreignId("comprobantes_id")->constrained("comprobantes");
            $table->text("response")->nullable();
            $table->text("json")->nullable();
            $table->text("firma")->nullable();
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
        Schema::dropIfExists('dtes');
    }
};
