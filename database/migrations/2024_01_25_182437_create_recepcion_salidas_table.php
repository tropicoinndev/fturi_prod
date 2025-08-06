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
        Schema::create('recepcion_salidas', function (Blueprint $table) {
            $table->id();
            $table->text('observacion')->nullable();
            $table->boolean('estado')->default(true);
            $table->boolean('facturada')->default(false);
            $table->foreignId('users_id')->constrained('users');
            $table->foreignId('clientes_id')->constrained('clientes');
            $table->foreignId('recepciones_id')->constrained('recepciones');
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
        Schema::dropIfExists('recepcion_salidas');
    }
};
