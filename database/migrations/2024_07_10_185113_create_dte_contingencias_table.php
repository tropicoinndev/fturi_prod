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
        Schema::create('dte_contingencias', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_comprobante')->nullable();
            $table->integer('modelo_factura');
            $table->integer('tipo_transmision');
            $table->string('otros')->nullable();
            $table->boolean('resuelto')->default(false);
            $table->foreignId('contingencias_id')->nullable()->constrained('contingencias');
            $table->foreignId('dtes_id')->constrained('dtes');
            $table->foreignId('users_id')->constrained('users');
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
        Schema::dropIfExists('dte_contingencias');
    }
};
