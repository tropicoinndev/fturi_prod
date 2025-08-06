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
        Schema::create('sujeto_excluidos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->integer('correlativo');
            $table->string('titular')->nullable();
            $table->text('observacion')->nullable();
            $table->boolean('estado')->default(true);
            $table->boolean('completo')->default(false);
            $table->boolean('enviado')->default(false);
            $table->foreignId('clientes_id')->nullable()->constrained('clientes');
            $table->foreignId('autoriza_users_id')->nullable()->constrained('users');
            $table->foreignId('cajas_id')->constrained('cajas');
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
        Schema::dropIfExists('sujeto_excluidos');
    }
};
