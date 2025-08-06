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
        Schema::create('huesped_reservas', function (Blueprint $table) {
            $table->id();
            $table->foreignId("huespedes_id")->constrained("huespedes");
            $table->foreignId("detalle_reservas_id")->constrained("detalle_reservas");
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
        Schema::dropIfExists('huesped_reservas');
    }
};
