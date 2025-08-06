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
        Schema::table('correlativo_sucursals', function (Blueprint $table) {
            $table->integer('tipo_dte')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('correlativo_sucursals', function (Blueprint $table) {
            $table->integer('tipo_dte')->nullable();
        });
    }
};
