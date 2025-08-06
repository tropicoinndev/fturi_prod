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
        Schema::create('dte_item_lotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dtes_id')->constrained('dtes');
            $table->foreignId('dte_lotes_id')->constrained('dte_lotes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dte_item_lotes');
    }
};
