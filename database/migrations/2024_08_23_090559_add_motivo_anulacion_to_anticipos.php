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
        if(!Schema::hasColumn('anticipos','motivo_anulacion'))
        {
            Schema::table('anticipos', function (Blueprint $table) {
                $table->text('motivo_anulacion')->nullable();
                $table->foreignId('users_anula_id')->nullable()->constrained('users');
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
        Schema::table('anticipos', function (Blueprint $table) {
            $table->dropForeign(['users_anula_id']);
            $table->dropColumn('users_anula_id');
        });
    }
};
