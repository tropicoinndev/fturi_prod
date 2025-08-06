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
        Schema::create('identificaciones', function (Blueprint $table) {
            $table->id();
            $table->string('identificacion', 100);
            //$table->string('codigo_mh',2);
            $table->string('regex', 100);
            $table->string('info', 200);
            $table->boolean('tipo_cliente')->default(true); #1 Natural, 2 Juridico.
            $table->boolean('estado')->default(false);

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
        Schema::dropIfExists('identificaciones');
    }
};
