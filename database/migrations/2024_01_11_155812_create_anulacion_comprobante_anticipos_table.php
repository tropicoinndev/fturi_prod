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
        Schema::create('anulacion_comprobante_anticipos', function (Blueprint $table) {
            $table->id();
            $table->double('monto');
            $table->foreignId('cobros_id')->constrained('cobros');
            $table->foreignId('anticipos_id')->constrained('anticipos');
            $table->foreignId('anulacion_comprobantes_id')->constrained('anulacion_comprobantes');
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
        Schema::dropIfExists('anulacion_comprobante_anticipos');
    }
};
