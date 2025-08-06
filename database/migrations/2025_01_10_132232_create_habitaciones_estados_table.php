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
        Schema::create('habitaciones_estados', function (Blueprint $table) {
            $table->id();
            $table->integer('codigo');
            $table->boolean('completado')->default(false);
            $table->string('justificacion', 200)->nullable();
            $table->foreignId('users_id')->constrained('users');
            $table->foreignId('habitaciones_id')->constrained('habitaciones');
            $table->foreignId('estado_habitaciones_id')->constrained('estado_habitaciones');
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
        Schema::dropIfExists('habitaciones_estados');
    }
};
