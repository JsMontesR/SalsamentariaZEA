<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Proveedor;
use Faker\Generator as Faker;

$factory->define(Proveedor::class, function (Faker $faker) {
    return [
        'nombre' => $faker->name,
        'telefono' => $faker->unique()->safeEmail,
        'direccion' => $faker->address,
    ];
});
