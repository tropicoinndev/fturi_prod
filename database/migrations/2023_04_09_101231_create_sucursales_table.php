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
        Schema::create('sucursales', function (Blueprint $table) {
            $table->id();
            $table->integer('codigo')->unique();
            $table->string('sucursal',100);
            $table->string('direccion',255);
            $table->string('telefono',20);
            $table->string('correo',100);
            $table->string('nit',20)->nullable();
            $table->string('nrc',20)->nullable();
            $table->boolean('iva')->default(false);
            $table->string('giro',255);
            $table->boolean('matriz')->default(false);
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
        Schema::dropIfExists('sucursales');
    }
};
