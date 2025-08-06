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
        Schema::table('comanda_detalles', function (Blueprint $table) {
            $table->boolean('prioridad')->default(false);
            $table->foreignId('users_asigna_id')->nullable()->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comanda_detalles', function (Blueprint $table) {
            //
        });
    }
};
