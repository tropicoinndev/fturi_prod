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
        Schema::create('turnos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->dateTime('apertura');
            $table->dateTime('cierre')->nullable();
            $table->boolean('estado')->default(true);
            $table->foreignId('apertura_users_id')->constrained('users');
            $table->foreignId('cierre_users_id')->nullable()->constrained('users');
            $table->foreignId('opcion_turnos_id')->constrained('opcion_turnos');
            $table->foreignId('cajas_id')->constrained('cajas');
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
        Schema::dropIfExists('turnos');
    }
};
