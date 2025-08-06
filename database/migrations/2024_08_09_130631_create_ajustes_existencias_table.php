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
        Schema::create('ajustes_existencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('existencias_id')->constrained('existencias');
            $table->integer('accion')->nullable();#1: Descarte, 2: Aumento
            $table->decimal('cantidad',11,4)->nullable();
            $table->bigInteger('users_id');#Usuario que agrega
            $table->boolean('estado')->default(true);
            $table->foreignId('ajustes_inventarios_id')->constrained('ajustes_inventarios');
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
        Schema::dropIfExists('ajustes_existencias');
    }
};
