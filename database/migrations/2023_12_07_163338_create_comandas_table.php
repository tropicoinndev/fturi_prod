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
        Schema::create('comandas', function (Blueprint $table) {
            $table->id();
            $table->boolean('estado')->default(true);
            $table->boolean('facturada')->default(false);
            $table->date('fecha');
            $table->foreignId('turnos_id')->constrained('turnos');
            $table->foreignId('cajas_id')->constrained('cajas');
            $table->string('titular', 200)->nullable();
            $table->foreignId('clientes_id')->nullable()->constrained('clientes');
            $table->boolean('comprobante')->default(false);
            $table->integer('tipo_comanda')->default(1)->comment('1 = Cliente, 2 = Credito, 3 = Cortesias, 4 = Huespedes');
            $table->foreignId('users_id')->constrained('users');
            $table->boolean('anulada')->default(false);
            $table->boolean('eliminada')->default(false);
            $table->integer('mesa');
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
        Schema::dropIfExists('comandas');
    }
};
