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
        Schema::create('existencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bodegas_id')->nullable()->constrained('bodegas')->onDelete('cascade');
            $table->integer('cantidad_historial');
            $table->integer('existencia');
            //$table->double('precio_costo',11,4)->default(0.00);
            $table->foreignId('productos_id')->constrained('productos')->onDelete('cascade');
            $table->foreignId('requisicion_detalles_id')->nullable()->constrained('requisicion_detalles')->onDelete('cascade');
            $table->date('vencimiento');
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
        Schema::dropIfExists('existencias');
    }
};
