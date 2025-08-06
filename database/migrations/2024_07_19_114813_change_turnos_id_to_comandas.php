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
        if (Schema::hasColumn('comandas', 'turnos_id')) {
            Schema::table('comandas', function (Blueprint $table) {
                $table->foreignId('turnos_id')->nullable()->change();
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
        Schema::table('comandas', function (Blueprint $table) {
            $table->foreignId('turnos_id')->nullable(false)->change();
        });
    }
};
