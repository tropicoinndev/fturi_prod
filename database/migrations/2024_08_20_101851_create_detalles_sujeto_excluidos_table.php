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
        Schema::create('detalles_sujeto_excluidos', function (Blueprint $table) {
            $table->id();
            $table->integer('tipo_item');
            $table->integer('cantidad');
            $table->integer('unidad_medida');
            $table->text('descripcion', 1000);
            $table->double('precio_unitario')->default(0);
            $table->double('compra')->default(0);
            $table->double('descuento')->default(0);
            $table->double('renta')->default(0);
            $table->foreignId('sujeto_excluidos_id')->constrained('sujeto_excluidos');
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
        Schema::dropIfExists('detalles_sujeto_excluidos');
    }
};
