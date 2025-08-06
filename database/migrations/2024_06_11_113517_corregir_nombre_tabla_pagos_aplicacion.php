<?php

use Illuminate\Database\Migrations\Migration;

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
        Schema::rename('pagos_aplicacion', 'aplicacion_pagos');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('aplicacion_pagos', 'pagos_aplicacion');
    }
};
