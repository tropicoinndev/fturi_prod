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
        Schema::create('precios', function (Blueprint $table) {
            $table->id();
            $table->string('detalle',200);
            $table->double('precio',11,4);
            $table->boolean('iva')->default(false);
            $table->boolean('advalorem')->default(false);
            $table->double('sugerido',11,4)->default(0.00);
            $table->boolean('propina')->default(false);
            $table->boolean('estado')->default(false);
            $table->foreignId('categorias_precios_id')->constrained('categorias_precios');
            $table->boolean('descuento')->default(false);
            $table->boolean('constante')->default(false);
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_final')->nullable();
            $table->integer('solicitud')->default(0)->comment('1=cocina, 2=bar, 0=sin solicitud');
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
        Schema::dropIfExists('precios');
    }
};
