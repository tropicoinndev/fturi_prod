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
        Schema::create('eventos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->time('inicio')->nullable();
            $table->time('finalizacion')->nullable();
            $table->dateTime('solicita')->nullable();
            $table->dateTime('autoriza')->nullable();
            $table->integer('minimo_personas')->default(0);
            $table->integer('maximo_personas')->default(0);
            $table->boolean('estado')->default(true);
            $table->boolean('facturado')->default(false);
            $table->boolean('comprobante')->default(false);
            $table->boolean('modificacion')->default(false);
            $table->string('observaciones', 200)->nullable();
            $table->string('observaciones_sonidos', 200)->nullable();
            $table->string('montaje', 200)->nullable();
            $table->string('titular', 200);
            $table->string('encargado', 200)->nullable();
            $table->foreignId('clientes_id')->constrained('clientes');
            $table->foreignId('sonidos_id')->nullable()->constrained('sonidos');
            $table->foreignId('tipo_eventos_id')->constrained('tipo_eventos');
            $table->foreignId('forma_pagos_id')->constrained('forma_pagos');
            $table->foreignId('montajes_id')->nullable()->constrained('montajes');
            $table->foreignId('users_id')->constrained('users');
            $table->foreignId('autoriza_users_id')->nullable()->constrained('users');
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
        Schema::dropIfExists('eventos');
    }
};
