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
        Schema::create('comanda_existencias', function (Blueprint $table) {
            $table->id();
            $table->integer("cantidad")->default(0);
            $table->foreignId("productos_id")->constrained("productos");
            $table->foreignId("existencias_id")->constrained("existencias");
            $table->foreignId("comanda_detalles_id")->constrained("comanda_detalles");
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
        Schema::dropIfExists('comanda_existencias');
    }
};
