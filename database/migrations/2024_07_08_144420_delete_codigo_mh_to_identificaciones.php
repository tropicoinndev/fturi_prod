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
        //validacion si existe el campo codigo_mh
        if(Schema::hasColumn('identificaciones','codigo_mh')){

        Schema::table('identificaciones', function (Blueprint $table) {
            $table->dropColumn('codigo_mh');

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
        Schema::table('identificaciones', function (Blueprint $table) {
            //
        });
    }
};
