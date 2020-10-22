<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductoRetiroTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('producto_retiro', function (Blueprint $table) {
            $table->unsignedBigInteger('retiro_id');
            $table->foreign('retiro_id')->references('id')->on('retiros')->onDelete('cascade');
            $table->unsignedBigInteger('producto_id');
            $table->foreign('producto_id')->references('id')->on('productos')->onDelete('cascade');
            $table->unsignedBigInteger('cantidad')->nullable();
            $table->string('unidad')->nullable();
            $table->unsignedBigInteger('costo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('producto_retiro');
    }
}
