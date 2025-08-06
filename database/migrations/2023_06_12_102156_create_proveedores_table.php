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
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id();
            $table->string('proveedor',100);
            $table->string('nrc',20)->nullable();
            $table->string('nit',20)->nullable();
            $table->string('dui',20)->nullable();
            $table->boolean('permite_credito')->default(false);
            $table->boolean('retencion')->default(false);
            $table->string('direccion',255);
            $table->foreignId('municipios_id')->constrained('municipios')->onDelete('cascade');
            $table->text('contactos');
            $table->text('informacion');
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
        Schema::dropIfExists('proveedores');
    }
};
