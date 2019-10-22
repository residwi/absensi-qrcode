<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Dosen;
use Faker\Generator as Faker;

$factory->define(Dosen::class, function (Faker $faker) {
    $jenis_kelamin = $faker->randomElement([1, 2]);
    $gender = [
        null,
        'male',
        'female'
    ];

    return [
        'nama' => $faker->name($gender[$jenis_kelamin]),
        'tanggal_lahir' => $faker->date(),
        'alamat' => $faker->address,
        'email' => $faker->unique()->email,
        'no_telp' => $faker->phoneNumber,
        'jenis_kelamin' => $jenis_kelamin,
        'photo' => 'default-user.png',
    ];
});
