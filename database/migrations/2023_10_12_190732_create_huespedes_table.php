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
        Schema::create('huespedes', function (Blueprint $table) {
            $table->id();
            $table->string("nombre");
            $table->date("nacimiento")->nullable();
            $table->string("telefono")->nullable();
            $table->boolean("estado")->default(true);
            $table->boolean("bloqueado")->default(false);
            $table->string("identificacion")->nullable();
            $table->foreignId("identificaciones_id")->nullable()->constrained('identificaciones');
            $table->foreignId("municipios_id")->nullable()->constrained('municipios');
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
        Schema::dropIfExists('huespedes');
    }
};
