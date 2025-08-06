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
        Schema::table('pago_anticipados', function (Blueprint $table) {
            $table->foreignId('cobros_id')->nullable()->constrained('cobros')->onDelete('cascade');
            $table->foreignId('comprobantes_id')->nullable()->constrained('comprobantes');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pago_anticipados', function (Blueprint $table) {
            //
        });
    }
};
