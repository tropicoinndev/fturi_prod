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
        Schema::create('evento_cuentas', function (Blueprint $table) {
            $table->id();
            $table->double('monto', 11, 4)->default(0.0000);
            $table->integer('origen');
            $table->bigInteger('origen_id');
            $table->boolean('estado')->default(true);
            $table->foreignId('users_id')->constrained('users');
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
        Schema::dropIfExists('evento_cuentas');
    }
};
