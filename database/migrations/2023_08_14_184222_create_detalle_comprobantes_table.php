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
        Schema::create('detalle_comprobantes', function (Blueprint $table) {
            $table->id();
            $table->integer("cantidad");
            $table->string("concepto");
            $table->double("iva",11, 4)->default(0);
            $table->double("cesc",11, 4)->default(0);
            $table->double("percepcion",11, 4)->default(0);
            $table->double("advalorem",11, 4)->default(0);
            $table->double("neto",11, 4)->default(0);
            $table->double("gravado",11, 4)->default(0);
            $table->double("exento",11, 4)->default(0);
            $table->double("propina",11, 4)->default(0);
            $table->double("total",11, 4)->default(0);
            $table->double("descuento",11, 4)->default(0);
            $table->foreignId("comprobantes_id")->constrained("comprobantes");
            $table->foreignId("descuentos_id")->nullable()->constrained("descuentos");
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
        Schema::dropIfExists('detalle_comprobantes');
    }
};
