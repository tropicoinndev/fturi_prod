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
        Schema::create('detalle_reservas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservaciones_id')->constrained('reservaciones');
            $table->foreignId('habitaciones_id')->constrained('habitaciones');
            $table->date('fecha_ingreso');
            $table->date('fecha_salida');
            $table->foreignId('tarifas_id')->constrained('tarifas');
            $table->foreignId('users_id')->constrained('users');
            $table->integer('cantidad_personas');
            $table->string('descripcion', 255)->nullable();
            $table->boolean('ingreso')->default(false);
            $table->boolean('estado')->default(true);
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
        Schema::dropIfExists('detalle_reservas');
    }
};
