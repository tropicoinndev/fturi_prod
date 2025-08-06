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
        Schema::create('solicitantes_clientes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clientes_id')->constrained('clientes');
            $table->foreignId('solicitantes_id')->constrained('solicitantes');
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
        Schema::dropIfExists('solicitantes_clientes');
    }
};
