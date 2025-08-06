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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',100);
            $table->string('direccion',255);
            $table->boolean('tipo_cliente')->default(true);
            $table->boolean('credito')->default(false);
            $table->foreignId('descuentos_id')->constrained('descuentos');
            $table->boolean('estado')->default(true);
            $table->foreignId('municipios_id')->constrained("municipios");
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
        Schema::dropIfExists('clientes');
    }
};
