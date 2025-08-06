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
        Schema::create('dte_lotes', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_lote');
            $table->dateTime('fecha_procesamiento')->nullable();
            $table->boolean('recibido');
            $table->foreignId('users_id')->constrained('users');
            $table->text('response')->nullable();
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
        Schema::dropIfExists('dte_lotes');
    }
};
