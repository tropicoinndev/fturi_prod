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
        Schema::create('ajustes_inventarios', function (Blueprint $table) {
            $table->id();
            $table->string('observacion',255)->nullable();
            $table->date('fecha_proceso');
            $table->foreignId('solicitante_users_id')->nullable()->constrained('users');
            $table->foreignId('realiza_users_id')->nullable()->constrained('users');
            $table->foreignId('autoriza_users_id')->nullable()->constrained('users');
            $table->boolean('estado');
            $table->boolean('solicitado');
            $table->boolean('autorizado');
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
        Schema::dropIfExists('ajustes_inventarios');
    }
};
