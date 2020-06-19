<?php

use App\Models\Movie;
use App\Models\MovieImage;
use Illuminate\Database\Seeder;

class MovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Movie::class,30)
            ->create()
            ->each(function(Movie $m){
                $m->images()->saveMany(
                  factory(MovieImage::class,rand(1,2))->make()
                );
            });
    }
}
