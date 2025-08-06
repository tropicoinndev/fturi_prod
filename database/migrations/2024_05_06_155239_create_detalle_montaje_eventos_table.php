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
        Schema::create('detalle_montaje_eventos', function (Blueprint $table) {
            $table->id();
            $table->integer('rotafolio_plumon')->nullable();
            $table->string('tipo_mesa', 200)->nullable();
            $table->string('tarima', 200)->nullable();
            $table->string('podium', 200)->nullable();
            $table->string('bandera', 200)->nullable();
            $table->string('otros', 200)->nullable();
            $table->string('equipo_montar', 200)->nullable();
            $table->string('pista_baile', 200)->nullable();
            $table->foreignId('eventos_id')->constrained('eventos');
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
        Schema::dropIfExists('detalle_montaje_eventos');
    }
};
