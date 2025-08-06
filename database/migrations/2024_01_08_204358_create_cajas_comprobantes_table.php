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
        Schema::create('cajas_comprobantes', function (Blueprint $table) {
            $table->id();
            $table->boolean('estado')->default(true);
            $table->foreignId('cajas_id')->constrained('cajas');
            $table->foreignId('origen_cajas_id')->constrained('cajas');
            
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
        Schema::dropIfExists('cajas_comprobantes');
    }
};
