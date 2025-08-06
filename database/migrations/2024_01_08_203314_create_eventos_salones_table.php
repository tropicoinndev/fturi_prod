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
        Schema::create('eventos_salones', function (Blueprint $table) {
            $table->id();
            $table->boolean('separado')->default(false);
            $table->foreignId('eventos_id')->constrained('eventos')->onDelete('cascade');
            $table->foreignId('salones_id')->constrained('salones')->onDelete('cascade');
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
        Schema::dropIfExists('eventos_salones');
    }
};
