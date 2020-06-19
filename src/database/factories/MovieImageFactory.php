<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\MovieImage;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\File;

$factory->define(MovieImage::class, function (Faker $faker) {
    $filepath=storage_path("images");
    if(!File::exists($filepath)){
        File::makeDirectory($filepath);
    }
    return [
        "path" => $faker->image($filepath)
    ];
});
