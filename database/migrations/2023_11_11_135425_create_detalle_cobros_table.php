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
        Schema::create('detalle_cobros', function (Blueprint $table) {
            $table->id();
            $table->integer('origen'); // 1 - Ordenes, 2 - Estadias, 3 - Comandas, 4 - Eventos.
            $table->integer('origen_id');
            $table->foreignId('cobros_id')->constrained('cobros');
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
        Schema::dropIfExists('detalle_cobros');
    }
};
