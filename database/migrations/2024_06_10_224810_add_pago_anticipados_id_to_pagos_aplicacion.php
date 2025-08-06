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
        Schema::table('pagos_aplicacion', function (Blueprint $table) {
            $table->foreignId('pago_anticipados_id')->constrained('pago_anticipados');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pagos_aplicacion', function (Blueprint $table) {
            //
        });
    }
};
