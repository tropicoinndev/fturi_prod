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
        Schema::create('anulaciones_detalle_comandas', function (Blueprint $table) {
            $table->id();
            $table->integer("cantidad");
            $table->string("observacion", 200)->nullable();
            $table->foreignId("comanda_detalles_id")->constrained('comanda_detalles');
            $table->foreignId("users_id")->constrained('users');
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
        Schema::dropIfExists('anulaciones_detalle_comandas');
    }
};
