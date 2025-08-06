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
        Schema::create('operaciones_reguladas', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->text('clase_servicio')->nullable();
            $table->text('observaciones')->nullable();
            $table->double('monto_sumatoria')->default(0);
            $table->double('efectivo')->default(0);
            $table->double('tarjeta')->default(0);
            $table->double('cheque')->default(0);
            $table->boolean('estado')->default(true);
            $table->boolean('completado')->default(false);
            $table->boolean('revisado')->default(false);
            $table->boolean('autorizado')->default(false);

            //Secciones
            //A
            $table->boolean('distinto')->nullable();
            $table->foreignId('seccion_a_persona_id')->nullable()->constrained('personas_naturales');

            //B
            $table->boolean('tipo_persona')->nullable();
            $table->foreignId('seccion_b_persona_id')->nullable()->constrained('personas_naturales');
            $table->foreignId('seccion_b_juridico_id')->nullable()->constrained('clientes');



            $table->foreignId('cajas_id')->nullable()->constrained('cajas');
            $table->foreignId('users_revisa_id')->nullable()->constrained('users');
            $table->foreignId('users_autoriza_id')->nullable()->constrained('users');
            $table->foreignId('users_id')->nullable()->constrained('users'); // Usuario que realiza el formulario
            $table->foreignId('comprobantes_id')->constrained('comprobantes');
            $table->foreignId('forma_pagos_id')->nullable()->constrained('forma_pagos');
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
        Schema::dropIfExists('operaciones_reguladas');
    }
};
