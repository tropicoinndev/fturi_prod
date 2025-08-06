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
        Schema::create('servicios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipo_servicios_id')->constrained('tipo_servicios');
            $table->string('servicio',255)->unique();
            $table->double('precio_unitario',11,4)->default(0.00);
            $table->double('sugerido',11,4)->default(0.00);
            $table->boolean('iva')->default(false);
            $table->boolean('cesc')->default(false);
            $table->boolean('advalorem')->default(false);
            $table->boolean('propina')->default(false);
            $table->boolean('descuento')->default(false);
            $table->boolean('precios')->default(false);
            $table->boolean('estado')->default(true);
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
        Schema::dropIfExists('servicios');
    }
};
