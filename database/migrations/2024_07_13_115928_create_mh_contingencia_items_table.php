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
        Schema::create('mh_contingencia_items', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_doc');
            $table->string('codigo_generacion');
            $table->foreignId('dtes_id')->constrained("dtes")->nullable();
            $table->foreignId('mh_contingencias_id')->constrained("mh_contingencias");
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
        Schema::dropIfExists('mh_contingencia_items');
    }
};
