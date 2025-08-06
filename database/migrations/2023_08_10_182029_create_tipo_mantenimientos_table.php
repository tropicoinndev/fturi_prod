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
        Schema::create('tipo_mantenimientos', function (Blueprint $table) {
            $table->id();
            $table->string('mantenimiento',100);
            $table->string('id_grupo_telegram',200)->nullable();
            $table->time('duracion_promedio');
            $table->boolean('estado')->default(false);
            $table->boolean('notificacion')->default(false);
            $table->foreignId('estado_habitacion_inicio_id')->nullable()->constrained('estado_habitaciones');
            $table->foreignId('estado_habitacion_completado_id')->nullable()->constrained('estado_habitaciones');
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
        Schema::dropIfExists('tipo_mantenimientos');
    }
};