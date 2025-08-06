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
        Schema::create('abonos', function (Blueprint $table) {
            $table->id();
            $table->date("fecha");
            $table->double("monto", 8, 2)->default(0);
            $table->text("concepto")->nullable();
            $table->boolean("estado")->default(false);
            $table->boolean("eliminado")->default(false);
            $table->foreignId("turnos_id")->nullable()->constrained('turnos');
            $table->foreignId("forma_pagos_id")->constrained('forma_pagos');
            $table->foreignId("clientes_id")->constrained('clientes');
            $table->foreignId("users_id")->constrained('users');
            $table->foreignId("caja_users_id")->nullable()->constrained('users');
            $table->foreignId("elimina_users_id")->nullable()->constrained('users');
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
        Schema::dropIfExists('abonos');
    }
};
