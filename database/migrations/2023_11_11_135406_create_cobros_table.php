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
        Schema::create('cobros', function (Blueprint $table) {
            $table->id();
            $table->text('titular')->nullable();
            $table->date('fecha');
            $table->integer('tipo_comprobante');
            $table->boolean('estado')->default(true);
            $table->boolean('facturado')->default(false);
            $table->foreignId('cajas_id')->constrained('cajas');
            $table->foreignId('users_id')->constrained('users');
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
        Schema::dropIfExists('cobros');
    }
};
