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
        Schema::create('habitaciones', function (Blueprint $table) {
            $table->id();
            $table->integer('numero_habitacion');
            $table->foreignId('tipo_habitaciones_id')->constrained('tipo_habitaciones');
            $table->foreignId('forma_habitaciones_id')->constrained('forma_habitaciones');
            $table->foreignId('estado_habitaciones_id')->constrained('estado_habitaciones');
            $table->foreignId('ubicacion_habitaciones_id')->constrained('ubicacion_habitaciones');
            $table->string('telefono',12);
            $table->string('extension',4);
            $table->string('descripcion',255)->nullable();
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
        Schema::dropIfExists('habitaciones');
    }
};
