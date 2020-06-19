<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\MovieImage;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\File;

$factory->define(MovieImage::class, function (Faker $faker) {
    $filepath=storage_path("app/images");
    if(!File::exists($filepath)){
        File::makeDirectory($filepath);
    }
    $imagePath = pathinfo($faker->image($filepath,200,200));
    return [
        "path" => sprintf('images/%s%s',$imagePath["filename"],
            key_exists("extension",$imagePath)?".".$imagePath["extension"]:"")
    ];
});
