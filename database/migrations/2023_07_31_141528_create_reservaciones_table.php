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
        Schema::create('reservaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clientes_id')->nullable()->constrained('clientes');
            $table->string('titular', 100)->nullable();
            $table->string('contacto', 100)->nullable();
            $table->foreignId('tipo_reservaciones_id')->constrained('tipo_reservaciones');
            $table->boolean('eliminado')->default(false);
            $table->string('razon_eliminacion', 255)->nullable();
            $table->boolean('estado')->default(true);
            $table->boolean('completa')->default(false);
            $table->foreignId("users_id")->constrained("users");
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
        Schema::dropIfExists('reservaciones');
    }
};
