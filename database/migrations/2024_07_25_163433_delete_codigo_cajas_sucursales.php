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
        //sucursales
        if(Schema::hasColumn('sucursales','codigo')){
            Schema::table('sucursales', function (Blueprint $table) {
                $table->dropColumn('codigo');
            });
        }
        //cajas
        if (Schema::hasColumn('cajas', 'codigo')) {
            Schema::table('cajas', function (Blueprint $table) {
                $table->dropColumn('codigo');
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
        Schema::table('sucursales', function (Blueprint $table) {
            //
        });
    }
};
