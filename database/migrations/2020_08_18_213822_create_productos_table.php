<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string("nombre");
            $table->unsignedBigInteger("costounitario")->nullable();
            $table->unsignedBigInteger("preciounitario")->nullable();
            $table->unsignedBigInteger("utilidadunitaria")->nullable();
            $table->unsignedBigInteger("stockunitario")->nullable();
            $table->float("costokilo")->nullable();
            $table->float("preciokilo")->nullable();
            $table->float("utilidadkilo")->nullable();
            $table->float("stockkilo")->nullable();
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
        Schema::dropIfExists('productos');
    }
}
