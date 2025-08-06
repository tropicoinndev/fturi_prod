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
     * cSpell:ignore recepcion, cesc, observacion, eliminacion
     */
    public function up()
    {
        Schema::create('recepcion_cargos', function (Blueprint $table) {
            $table->id();
            $table->integer('cantidad');
            $table->double('precio');
            $table->double('iva');
            $table->double('cesc');
            $table->double('propina');
            $table->boolean('estado')->default(true);
            $table->boolean('facturado')->default(false);
            $table->string('observacion')->nullable();
            $table->string('eliminacion', 200)->nullable();
            $table->date('fecha_eliminacion')->nullable();
            $table->foreignId('cargos_id')->constrained('cargos');
            $table->foreignId('recepciones_id')->constrained('recepciones');
            $table->foreignId('users_id')->constrained('users');
            $table->foreignId('eliminacion_users_id')->nullable()->constrained('users');
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
        Schema::dropIfExists('recepcion_cargos');
    }
};
