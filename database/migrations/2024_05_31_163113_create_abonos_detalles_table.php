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
        Schema::create('abonos_detalles', function (Blueprint $table) {
            $table->id();
            $table->double('monto', 8, 2)->default(0);
            $table->boolean('estado')->default(true);
            $table->foreignId('users_id')->constrained('users');
            $table->foreignId('abonos_id')->constrained('abonos');
            $table->foreignId('comprobantes_id')->constrained('comprobantes');
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
        Schema::dropIfExists('abonos_detalles');
    }
};
