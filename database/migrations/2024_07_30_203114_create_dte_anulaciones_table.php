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
        Schema::create('dte_anulaciones', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_generacion');
            $table->string('codigo_generacion_r')->nullable();
            $table->dateTime('fecha_procesamiento')->nullable();
            $table->string('tipo_dte');
            $table->boolean('error')->default(false);
            $table->string('sello_recibido')->nullable();
            $table->foreignId('anulacion_comprobantes_id')->constrained('anulacion_comprobantes');
            $table->foreignId('dtes_id')->constrained('dtes');
            $table->foreignId('users_id')->constrained('users');
            $table->text('response');
            $table->text('json');
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
        Schema::dropIfExists('dte_anulaciones');
    }
};
