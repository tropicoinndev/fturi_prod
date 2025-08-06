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
        Schema::create('recepciones', function (Blueprint $table) {
            $table->id();
            $table->string("titular")->nullable();
            $table->string("contacto")->nullable();
            $table->date("fecha_ingreso");
            $table->date("fecha_salida");
            $table->text("descripcion")->nullable();
            $table->boolean("estado")->default(true);
            $table->boolean("facturada")->default(false);
            $table->boolean("eliminado")->default(false);
            $table->foreignId('habitaciones_id')->constrained('habitaciones');
            $table->foreignId('tarifas_id')->constrained('tarifas');
            $table->foreignId('users_id')->constrained('users');
            $table->foreignId('users_eliminado_id')->nullable()->constrained('users');
            $table->foreignId('detalle_reservas_id')->nullable()->constrained('detalle_reservas');
            $table->foreignId('clientes_id')->nullable()->constrained('clientes');
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
        Schema::dropIfExists('recepciones');
    }
};
