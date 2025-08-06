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
        Schema::create('comprobantes', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->string('titular',100);
            $table->integer('correlativo');
            $table->string('descripcion',255)->nullable();
            $table->double('iva',11,4)->default(0.00);
            $table->double('cesc',11,4)->default(0.00);
            $table->double('percepcion',11,4)->default(0.00);
            $table->double('advalorem',11,4)->default(0.00);
            $table->double('neto',11,4)->default(0.00);
            $table->double('gravado',11,4)->default(0.00);
            $table->double('exento',11,4)->default(0.00);
            $table->double('propina',11,4)->default(0.00);
            $table->double('total',11,4)->default(0.00);
            $table->foreignId('clientes_id')->nullable()->constrained('clientes');
            $table->foreignId('users_id')->constrained('users');
            $table->foreignId('turnos_id')->constrained('turnos');
            $table->foreignId('tipo_comprobantes_id')->constrained('tipo_comprobantes');
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
        Schema::dropIfExists('comprobantes');
    }
};
