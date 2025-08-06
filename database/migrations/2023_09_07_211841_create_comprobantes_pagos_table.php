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
        Schema::create('comprobantes_pagos', function (Blueprint $table) {
            $table->id();
            $table->double("monto",11, 2)->default(0);
            $table->foreignId("comprobantes_id")->constrained("comprobantes");
            $table->foreignId("forma_pagos_id")->constrained("forma_pagos");
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
        Schema::dropIfExists('comprobantes_pagos');
    }
};
