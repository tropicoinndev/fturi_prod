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
        Schema::create('requisicion_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('productos_id')->constrained('productos')->onDelete('cascade');
            $table->integer('cantidad');
            $table->foreignId('users_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('lotes_id')->nullable()->constrained('lotes')->onDelete('cascade');
            $table->foreignId('requisiciones_id')->constrained('requisiciones')->onDelete('cascade');
            $table->boolean('estado')->default(true);
            //$table->date('vencimiento');
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
        Schema::dropIfExists('requisicion_detalles');
    }
};
