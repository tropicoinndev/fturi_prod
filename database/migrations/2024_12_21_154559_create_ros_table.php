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
        Schema::create('ros', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->double('monto')->default(0);
            $table->text('clase_producto')->nullable();
            $table->text('nombre')->nullable();
            $table->text('observaciones')->nullable();
            $table->text('numero_identificacion')->nullable();
            $table->text('cargo')->nullable();
            $table->foreignId('users_id')->nullable()->constrained('users');
            $table->foreignId('identificaciones_id')->nullable()->constrained('identificaciones');
            $table->foreignId('forma_pagos_id')->nullable()->constrained('forma_pagos');
            $table->foreignId('comprobantes_id')->nullable()->constrained('comprobantes');
            $table->foreignId('sucursales_id')->nullable()->constrained('sucursales');
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
        Schema::dropIfExists('ros');
    }
};
