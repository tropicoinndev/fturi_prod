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
        Schema::create('anticipos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->text('concepto');
            $table->date('fecha_aplicacion')->nullable();
            $table->double('monto', 8, 4)->default(0);
            $table->double('monto_historico', 8, 4)->default(0);
            $table->boolean('estado')->default(true);
            $table->boolean('anulado')->default(false);
            $table->boolean('separado')->default(false);
            $table->foreignId('turnos_id')->constrained('turnos');
            $table->foreignId('clientes_id')->constrained('clientes');
            $table->foreignId('users_id')->constrained('users');
            $table->foreignId('forma_pagos_id')->constrained('forma_pagos');
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
        Schema::dropIfExists('anticipos');
    }
};
