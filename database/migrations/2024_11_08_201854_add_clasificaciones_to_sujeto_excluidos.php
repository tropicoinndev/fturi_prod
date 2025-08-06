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
        Schema::table('sujeto_excluidos', function (Blueprint $table) {
            $table->integer('tipo_operacion')->default(0);
            $table->integer('clasificacion')->default(0);
            $table->integer('sector')->default(0);
            $table->integer('tipo_clasificacion')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sujeto_excluidos', function (Blueprint $table) {
            //
        });
    }
};
