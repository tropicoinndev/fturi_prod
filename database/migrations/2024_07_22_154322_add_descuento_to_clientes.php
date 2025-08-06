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
        if(!Schema::hasColumn('clientes','descuento')){
            Schema::table('clientes', function (Blueprint $table) {
            $table->boolean('descuento')->default(false);
        });
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasColumn('clientes', 'descuento')){
            Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn('descuento');
            });
        }
    }
};
