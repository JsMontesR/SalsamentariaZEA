<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movimientos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("parteCrediticia")->default(0);
            $table->unsignedBigInteger("parteEfectiva")->default(0);
            $table->unsignedBigInteger("dineroRecibido")->default(0);
            $table->unsignedBigInteger("cambio")->default(0);
            $table->unsignedBigInteger('movimientoable_id');
            $table->string('tipo')->default("Ingreso");
            $table->string('movimientoable_type');
            $table->softDeletes();
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
        Schema::dropIfExists('movimientos');
    }
}
