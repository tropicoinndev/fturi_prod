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
        Schema::create('personas_naturales', function (Blueprint $table) {
            $table->id();
            $table->string('apellidos',250);
            $table->string('nombre',250);
            $table->string('nacimiento',250)->nullable();
            $table->foreignId('departamentos_id')->nullable()->constrained('departamentos');
            $table->date('fecha_nacimiento')->nullable();
            $table->foreignId('paises_id')->nullable()->constrained('paises');
            $table->string('estado_civil',50)->nullable();
            $table->foreignId('identificaciones_id')->constrained('identificaciones');
            $table->string('identificacion',50);
            $table->string('domicilio',250)->nullable();
            $table->string('observaciones',250)->nullable();
            $table->boolean('persona_riesgo')->default(false);
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
        Schema::dropIfExists('personas_naturales');
    }
};
