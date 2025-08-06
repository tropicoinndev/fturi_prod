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
        Schema::create('anulacion_comandas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_solicita_id')->constrained('users');
            $table->string('observacion',200);
            $table->foreignId('comandas_id')->constrained('comandas');
            $table->foreignId('users_autoriza_id')->nullable()->constrained('users');
            $table->timestamp('solicitud')->nullable();
            $table->timestamp('autorizacion');
            $table->integer('estado')->default(3)->comment('1=autoriza, 2=niega, 3=sin autorizar');
            $table->string('observacion_autoriza',200)->nullable();

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
        Schema::dropIfExists('anulacion_comandas');
    }
};
