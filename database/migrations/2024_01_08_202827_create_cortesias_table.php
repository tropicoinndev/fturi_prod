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
        Schema::create('cortesias', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->double('monto', 11, 4)->default(0.00);
            $table->integer('origen');
            $table->bigInteger('origen_id');
            $table->boolean('estado')->default(true);
            $table->boolean('facturada')->default(false);
            $table->boolean('autorizado')->default(false);
            $table->string('observacion', 200)->nullable();
            $table->foreignId('autoriza_users_id')->nullable()->constrained('users');
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
        Schema::dropIfExists('cortesias');
    }
};
