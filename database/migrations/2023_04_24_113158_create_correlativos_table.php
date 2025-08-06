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
        Schema::create('correlativos', function (Blueprint $table) {
            $table->id();
            $table->integer('inicio');
            $table->integer('actual');
            $table->integer('final');
            $table->foreignId('cajas_id')->constrained('cajas');
            $table->foreignId('users_id')->constrained('users');
            $table->foreignId('tipo_comprobantes_id')->constrained('tipo_comprobantes');
            $table->boolean('estado')->default(true);
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
        Schema::dropIfExists('correlativos');
    }
};
