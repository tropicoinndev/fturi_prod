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
        if (!Schema::hasColumn('huespedes', 'paises_id')) {
            Schema::table('huespedes', function (Blueprint $table) {
                $table->foreignId("paises_id")->nullable()->constrained('paises');
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
        Schema::table('huespedes', function (Blueprint $table) {
            $table->dropColumn('paises_id');
        });
    }
};
