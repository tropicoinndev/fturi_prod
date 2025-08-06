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
        Schema::create('dte_logs', function (Blueprint $table) {
            $table->id();
            $table->dateTime('hora');
            $table->text('response');
            $table->foreignId('dtes_id')->nullable()->constrained('dtes');
            $table->foreignId('comprobantes_id')->nullable()->constrained('comprobantes');
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
        Schema::dropIfExists('dte_logs');
    }
};
