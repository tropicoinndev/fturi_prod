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
        Schema::create('clientes_contactos', function (Blueprint $table) {
            $table->id();
            $table->string('valor',100);
            $table->foreignId('clientes_id')->constrained('clientes');
            $table->foreignId('contactos_id')->constrained('contactos');
            $table->text('observaciones')->nullable();
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
        Schema::dropIfExists('clientes_contactos');
    }
};
