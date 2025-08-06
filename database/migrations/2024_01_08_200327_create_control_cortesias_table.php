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
        Schema::create('control_cortesias', function (Blueprint $table) {
            $table->id();
            $table->double('monto', 11, 4)->default(0.0000);
            $table->boolean('estado')->default(true);
            $table->boolean('eliminado')->default(false);
            $table->string('titular', 200);
            $table->foreignId('tipo_cortesias_id')->constrained('tipo_cortesias');
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
        Schema::dropIfExists('control_cortesias');
    }
};
