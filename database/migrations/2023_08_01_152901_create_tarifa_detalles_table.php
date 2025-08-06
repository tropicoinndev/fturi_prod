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
        Schema::create('tarifa_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipo_habitaciones_id')->constrained('tipo_habitaciones')->onDelete('cascade');
            $table->foreignId('forma_habitaciones_id')->constrained('forma_habitaciones')->onDelete('cascade');
            $table->foreignId('tarifas_id')->constrained('tarifas')->onDelete('cascade');
            
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
        Schema::dropIfExists('tarifa_detalles');
    }
};
