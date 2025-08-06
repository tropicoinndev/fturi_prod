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
        Schema::create('anticipo_reservacions', function (Blueprint $table) {
            $table->id();
            $table->integer('tipo_reservacion');
            $table->bigInteger('reservacion_id');
            $table->foreignId('anticipos_id')->constrained('anticipos');
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
        Schema::dropIfExists('anticipo_reservacions');
    }
};
