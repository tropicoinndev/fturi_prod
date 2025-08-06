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
        Schema::create('habitacion_camas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('habitaciones_id')->constrained('habitaciones')->onDelete('cascade');
            $table->foreignId('tipo_camas_id')->constrained('tipo_camas')->onDelete('cascade');
            $table->integer('cantidad');
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
        Schema::dropIfExists('habitacion_camas');
    }
};
