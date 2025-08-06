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
        Schema::create('bitacora_combinacions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('uesrs_id')->constarined('users');
            $table->foreignId('comanda_origen_id')->constrained('comandas');
            $table->foreignId('comanda_destino_id')->constrained('comandas');
            $table->double('cantidad', 11,4);
            $table->string('bitacora', 200);
            $table->double('precio', 11,4);

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
        Schema::dropIfExists('bitacora_combinacions');
    }
};
