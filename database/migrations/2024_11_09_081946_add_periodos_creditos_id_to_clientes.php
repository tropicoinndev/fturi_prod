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
        Schema::table('clientes', function (Blueprint $table) {
            $table->foreignId('periodos_creditos_id')->nullable()->constrained('periodos_creditos');
            $table->boolean('empleado')->default(false);
            $table->boolean('notificacion')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropForeign(['periodos_creditos_id']);#Eliminar clave foránea
            $table->dropColumn('periodos_creditos_id');#Eliminar columna

            $table->dropColumn('empleado');
            $table->dropColumn('notificacion');
        });
    }
};
