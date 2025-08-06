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
        // Cambiar en la tabla 'existencias'
        if (Schema::hasColumn('existencias', 'vencimiento')) {
            Schema::table('existencias', function (Blueprint $table) {
                $table->date('vencimiento')->nullable()->change();
            });
        }

        // Cambiar en la tabla 'requisicion_detalles'
        if (Schema::hasColumn('requisicion_detalles', 'vencimiento')) {
            Schema::table('requisicion_detalles', function (Blueprint $table) {
                $table->date('vencimiento')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
        Schema::table('existencias', function (Blueprint $table) {
            $table->date('vencimiento')->nullable(false)->change();
        });

        Schema::table('requisicion_detalles', function (Blueprint $table) {
            $table->date('vencimiento')->nullable(false)->change();
        });
    }
};
