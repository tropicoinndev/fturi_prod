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
        Schema::create('anticipos_cobros', function (Blueprint $table) {
            $table->id();
            $table->double('monto', 8, 2)->default(0);
            $table->boolean('aplicado')->default(false);
            $table->foreignId('cobros_id')->constrained('cobros');
            $table->foreignId('anticipos_id')->constrained('anticipos');
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
        Schema::dropIfExists('anticipos_cobros');
    }
};
