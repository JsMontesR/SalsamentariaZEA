<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Movimiento;
use Faker\Generator as Faker;

$factory->define(Movimiento::class, function (Faker $faker) {
    return [
        'parteCrediticia' => $faker->randomNumber(),
        'parteEfectiva' => $faker->randomNumber(),
        'movimientoable_id' => 2,
        'empleado_id' => 1,
        'tipo' => $faker->randomElement(["Egreso"]),
        'movimientoable_type' => "App\Entrada",
        'caja_id' => 1
    ];
});
