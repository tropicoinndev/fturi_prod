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
        Schema::create('api_mhs', function (Blueprint $table) {
            $table->id();
            $table->string("user");
            $table->string("token");
            $table->string("rol");
            $table->string("roles");
            $table->string("token_type");
            $table->dateTime("sesion");
            $table->dateTime("finalizacion");
            $table->boolean("estado")->default(false);
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
        Schema::dropIfExists('api_mhs');
    }
};
