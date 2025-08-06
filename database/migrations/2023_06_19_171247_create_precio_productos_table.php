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
        Schema::create('precio_productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('precios_id')->constrained('precios')->onDelete('cascade');
            $table->foreignId('productos_id')->constrained('productos')->onDelete('cascade');
            $table->integer('descargo');
            $table->boolean('produccion')->default(false);
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
        Schema::dropIfExists('precio_productos');
    }
};
