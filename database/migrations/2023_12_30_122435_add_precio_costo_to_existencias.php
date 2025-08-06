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
        Schema::table('existencias', function (Blueprint $table) {
            $table->double('precio_costo',11,4)->default(0.00);
            $table->boolean('estado')->default(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('existencias', function (Blueprint $table) {
            
            $table->dropColumn('precio_costo');
            $table->dropColumn('estado');
            
        });
    }
};
