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
        Schema::create('pago_anticipados', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->double('monto', 11, 4)->default(0.00);
            $table->double('monto_historico', 11, 4)->default(0.00);
            $table->boolean('estado')->default(true);
            $table->boolean('anulado')->default(false);
            $table->string('concepto', 200);
            $table->foreignId('turnos_id')->constrained('turnos');
            $table->foreignId('cajas_id')->constrained('cajas');
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
        Schema::dropIfExists('pago_anticipados');
    }
};
