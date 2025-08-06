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
        if(!Schema::hasColumn('ordenes','turnos_id')) {
        Schema::table('ordenes', function (Blueprint $table) {
            $table->foreignId('turnos_id')->nullable()->constrained('turnos');

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
        Schema::table('ordenes', function (Blueprint $table) {
            $table->dropForeign(['turnos_id']);
            $table->dropColumn('turnos_id');
        });
    }
};
