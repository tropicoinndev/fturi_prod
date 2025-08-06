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
        Schema::create('ordenes', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->string('titular',100)->nullable();
            $table->string('descripcion',255)->nullable();
            $table->foreignId('clientes_id')->nullable()->constrained('clientes');
            $table->foreignId('cajas_id')->constrained('cajas');
            $table->boolean('estado')->default(true);
            $table->boolean('comprobante')->default(false);
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
        Schema::dropIfExists('ordenes');
    }
};
