<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Matkul;
use Faker\Generator as Faker;

$factory->define(Matkul::class, function (Faker $faker) {
    return [
        'kode' => strtoupper($faker->randomLetter) . strtoupper($faker->randomLetter) . $faker->unique()->numberBetween(100, 300), // CC101
        'nama' => $faker->sentence($faker->numberBetween(2, 4)),
        'ruang' => strtoupper($faker->randomLetter) . $faker->unique()->numberBetween(100, 300) // A302
    ];
});
