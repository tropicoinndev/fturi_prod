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
        Schema::table('requisicion_detalles', function (Blueprint $table) {
            // Elimina la restricción de unicidad y establece el campo como nullable e integer
            //$table->dropUnique('requisicion_detalles_lote_origen_unique');
            $table->integer('lote_origen')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('requisicion_detalles', function (Blueprint $table) {
            //
        });
    }
};
