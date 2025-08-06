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
        Schema::create('recepcion_salida_anticipadas', function (Blueprint $table) {
            $table->id();
            $table->text('razon');
            $table->date("fecha_ingreso");
            $table->date("fecha_salida");
            $table->foreignId('users_id')->constrained('users');
            $table->foreignId('recepciones_id')->constrained('recepciones');
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
        Schema::dropIfExists('recepcion_salida_anticipadas');
    }
};
