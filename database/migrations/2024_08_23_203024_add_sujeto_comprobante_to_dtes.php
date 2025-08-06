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
        Schema::table('dtes', function (Blueprint $table) {
            $table->dropForeign(['comprobantes_id']);
            $table->unsignedBigInteger('comprobantes_id')->nullable()->change();
            $table->foreign('comprobantes_id')->references('id')->on('comprobantes')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('sujeto_excluidos_id')->nullable()->constrained('sujeto_excluidos')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dtes', function (Blueprint $table) {
            //
        });
    }
};
