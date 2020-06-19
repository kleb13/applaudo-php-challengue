<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Movie;
use Faker\Generator as Faker;

$factory->define(Movie::class, function (Faker $faker) {
    return [
    "stock" => $faker->numberBetween(1,50),
    "title" => $faker->text(255),
    "description" => $faker->paragraph,
    "availability" => $faker->boolean,
    "sale_price" => $faker->randomFloat(2,9,80),
    "rental_price" => $faker->randomFloat(2,5,15)
    ];
});
