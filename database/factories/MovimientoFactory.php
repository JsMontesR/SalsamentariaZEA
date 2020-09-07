<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Movimiento;
use Faker\Generator as Faker;

$factory->define(Movimiento::class, function (Faker $faker) {
    return [
        'parteCrediticia' => $faker->randomNumber(),
        'parteEfectiva' => $faker->randomNumber(),
        'movimientoable_id' => 1,
        'ingreso' => $faker->numberBetween($min = 0, $max = 1),
        'movimientoable_type' => "App\Nomina",
        'caja_id' => 1
    ];
});
