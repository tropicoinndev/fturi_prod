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
        Schema::table('clientes_identificaciones', function (Blueprint $table) {
            if (Schema::hasColumn('clientes_identificaciones', 'numero')) {
                $table->dropUnique('clientes_identificaciones_numero_unique');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clientes_identificaciones', function (Blueprint $table) {
            //
        });
    }
};
