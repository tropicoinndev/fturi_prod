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
        Schema::create('mantenimientos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->datetime('asignacion')->nullable();
            $table->datetime('inicio')->nullable();
            $table->datetime('finalizacion')->nullable();
            $table->enum('estado', ['sin asignar', 'asignado','confirmado','iniciado',
            'completado','finalizado','incompleto','negado'])->default('sin asignar');
            $table->foreignId('asignado_users_id')->nullable()->constrained('users');
            $table->foreignId('supervisor_users_id')->nullable()->constrained('users');
            $table->foreignId('creacion_users_id')->constrained('users');
            $table->datetime('confirmacion_asignacion')->nullable();
            $table->foreignId('tipo_mantenimientos_id')->constrained('tipo_mantenimientos');
            $table->foreignId('habitaciones_id')->constrained('habitaciones');
            $table->string('observacion',200)->nullable();
            $table->string('bitacora_asignado',200)->nullable();
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
        Schema::dropIfExists('mantenimientos');
    }
};
