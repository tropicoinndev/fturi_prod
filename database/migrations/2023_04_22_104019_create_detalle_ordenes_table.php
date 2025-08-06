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
        Schema::create('detalle_ordenes', function (Blueprint $table) {
            $table->id();
            $table->integer('cantidad');
            $table->double('precio_unitario',11,4)->default(0.00);
            $table->double('neto',11,4)->default(0.00);
            $table->double('iva',11,4)->default(0.00);
            $table->double('cesc',11,4)->default(0.00);
            $table->double('advalorem',11,4)->default(0.00);
            $table->double('propina',11,4)->default(0.00);
            $table->bigInteger('descuentos_id')->unsigned()->nullable();
            $table->foreignId('servicios_id')->constrained('servicios');
            $table->bigInteger('ordenes_id')->unsigned()->nullable();
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
        Schema::dropIfExists('detalle_ordenes');
    }
};
