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
        Schema::create('compras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proveedores_id')->constrained('proveedores');
            $table->dateTime('fecha');
            $table->date('fecha_factura');
            $table->double('total',11,4)->default(0.00);
            $table->double('iva',11,4)->default(0.00);
            $table->double('retencion',11,4)->default(0.00);
            $table->double('percepcion',11,4)->default(0.00);
            $table->double('fovial',11,4)->nullable()->default(0.00);
            $table->string('correlativo',255);
            $table->string('serie',255);
            $table->foreignId('tipo_pagos_id')->constrained('tipo_pagos');
            $table->foreignId('users_id')->constrained('users');
            $table->integer('requisiones_id')->nullable()->onDelete('cascade');
            $table->boolean('completado')->default(false);
            $table->boolean('estado')->default(false);
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
        Schema::dropIfExists('compras');
    }
};
