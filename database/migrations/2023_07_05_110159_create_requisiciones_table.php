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
        Schema::create('requisiciones', function (Blueprint $table) {
            $table->id();
            $table->dateTime('fecha');
            $table->string('solicitud',255)->nullable();
            $table->foreignId('bodega_salida_id')->nullable()->constrained('bodegas');
            $table->foreignId('bodega_entrada_id')->nullable()->constrained('bodegas');
            $table->foreignId('user_autorizacion_id')->nullable()->constrained('users');
            $table->foreignId('user_creacion_id')->constrained('users');
            $table->foreignId('user_elimina_id')->nullable()->constrained('users');
            $table->integer('estado')->comment('Pendiente | Terminada | Aprobada | Negada');
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
        Schema::dropIfExists('requisiciones');
    }
};
