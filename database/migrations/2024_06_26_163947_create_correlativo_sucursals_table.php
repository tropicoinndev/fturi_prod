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
        Schema::create('correlativo_sucursals', function (Blueprint $table) {
            $table->id();
            $table->integer("inicio")->default(1);
            $table->bigInteger("actual")->default(0);
            $table->bigInteger("final")->default(999999999999999);
            $table->year("year"); //Año actual
            $table->boolean("estado")->default(true);
            $table->foreignId("sucursales_id")->constrained("sucursales");
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
        Schema::dropIfExists('correlativo_sucursals');
    }
};
