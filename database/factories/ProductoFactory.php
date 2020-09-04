<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Producto;
use Faker\Generator as Faker;

$factory->define(Producto::class, function (Faker $faker) {
    return [
        'nombre' => $faker->name,
        'costo' => $faker->randomNumber(),
        'categoria' => "Granel",
        'utilidad' => $faker->randomNumber(),
        'precio' => $faker->randomNumber(),
        'stock' => $faker->randomNumber(),
        'tipo_id' => 1,
    ];
});
