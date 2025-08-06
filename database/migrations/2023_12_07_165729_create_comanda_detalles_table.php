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
        Schema::create('comanda_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comandas_id')->constrained('comandas');
            $table->double('cantidad', 11, 4);
            $table->double('precio', 11, 4);
            $table->boolean('iva')->default(false);
            $table->boolean('propina')->default(false);
            $table->boolean('advalorem')->default(false);
            $table->timestamp('solicitud')->nullable();
            $table->timestamp('aceptacion')->nullable();
            $table->time('espera')->nullable();
            $table->string('observaciones', 200)->nullable();
            $table->boolean('cancelado')->default(false);
            $table->boolean('anulado')->default(false);
            $table->timestamp('entregado')->nullable();
            $table->time('incremento_tiempo')->nullable();
            $table->foreignId('precios_id')->constrained('precios');
            $table->foreignId('users_comanda_id')->constrained('users');
            $table->foreignId('users_acepta_id')->nullable()->constrained('users');
            $table->foreignId('descuentos_id')->nullable()->constrained('descuentos');

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
        Schema::dropIfExists('comanda_detalles');
    }
};
