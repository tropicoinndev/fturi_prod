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
        Schema::create('lotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('compras_id')->constrained('compras')->onDelete('cascade');
            $table->integer('cantidad');
            $table->double('precio',11,4)->default(0.00);
            $table->double('iva',11,4)->default(0.00);
            $table->double('total',11,4)->default(0.00);
            $table->double('retencion',11,4)->default(0.00);
            $table->foreignId('productos_id')->nullable()->constrained('productos')->onDelete('cascade');
            $table->date('fecha_vencimiento')->nullable();
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
        Schema::dropIfExists('lotes');
    }
};
