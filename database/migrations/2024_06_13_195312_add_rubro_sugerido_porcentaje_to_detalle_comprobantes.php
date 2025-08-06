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
        Schema::table('detalle_comprobantes', function (Blueprint $table) {
            $table->foreignId("rubros_id")->nullable()->constrained("rubros");
            $table->double("sugerido")->default(0);
            $table->double("porcentaje_descuento")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('detalle_comprobantes', function (Blueprint $table) {
            //
        });
    }
};
