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
        Schema::create('montajes_galerias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('galerias_id')->constrained('galerias')->onDelete('cascade');
            $table->foreignId('montajes_id')->constrained('montajes')->onDelete('cascade');
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
        Schema::dropIfExists('montajes_galerias');
    }
};
