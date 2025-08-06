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
     * cSpell:ignore anulacion, observacion
     */
    public function up()
    {
        Schema::create('anulacion_comprobantes', function (Blueprint $table) {
            $table->id();
            $table->date('fecha')->nullable();
            $table->text('correlativo')->nullable();
            $table->text('observacion')->nullable();
            $table->text('response')->nullable();
            $table->boolean('aceptado')->default(false);
            $table->foreignId('users_id')->constrained('users');
            $table->foreignId('cajas_id')->constrained('cajas');
            $table->foreignId('turnos_id')->constrained('turnos');
            $table->foreignId('anulaciones_id')->constrained('anulaciones');
            $table->foreignId('comprobantes_id')->constrained('comprobantes');
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
        Schema::dropIfExists('anulacion_comprobantes');
    }
};
