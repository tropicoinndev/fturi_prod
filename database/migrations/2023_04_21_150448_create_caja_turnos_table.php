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
        Schema::create('caja_turnos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('opcion_turnos_id')->constrained('opcion_turnos')->onDelete('cascade');
            $table->foreignId('cajas_id')->constrained('cajas')->onDelete('cascade');
            $table->boolean('estado')->default(false);
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
        Schema::dropIfExists('caja_turnos');
    }
};
