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
        Schema::create('detalle_contribuyentes', function (Blueprint $table) {
            $table->id();
            $table->string('juridico', 250);
            $table->boolean('exento')->default(false);
            $table->boolean('exento_iva')->default(false);
            $table->boolean('exento_cesc')->default(false);
            $table->boolean('exento_advalorem')->default(false);
            $table->boolean('percepcion')->default(false);
            $table->foreignId('clientes_id')->constrained("clientes");
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
        Schema::dropIfExists('detalle_contribuyentes');
    }
};
