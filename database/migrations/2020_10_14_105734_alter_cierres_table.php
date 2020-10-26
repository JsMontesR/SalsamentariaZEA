<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCierresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cierres', function (Blueprint $table) {
            $table->unsignedBigInteger('caja_id');
            $table->foreign('caja_id')->references('id')->on('cajas')->onDelete('restrict');
            $table->unsignedBigInteger('cierre_id')->nullable();
            $table->foreign('cierre_id')->references('id')->on('cierres')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cierres', function (Blueprint $table) {
            $table->dropForeign(['caja_id']);
            $table->dropColumn('caja_id');
            $table->dropForeign(['cierre_id']);
            $table->dropColumn('cierre_id');
        });
    }
}
