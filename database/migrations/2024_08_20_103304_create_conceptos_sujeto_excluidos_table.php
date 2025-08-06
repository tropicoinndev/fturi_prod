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
        Schema::create('conceptos_sujeto_excluidos', function (Blueprint $table) {
            $table->id();
            $table->integer('tipo_item');
            $table->integer('unidad');
            $table->text('conceptos', 1000);
            $table->double('monto');
            $table->double('renta');
            $table->boolean('estado')->default(true);
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
        Schema::dropIfExists('conceptos_sujeto_excluidos');
    }
};
