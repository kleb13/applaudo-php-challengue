<?php

namespace Tests\Feature;

use App\Models\Like;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LikeControllerTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var User
     */
    static $user;

    static $movies;
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
        static::$user = factory(User::class)->create();
        static::$movies = factory(Movie::class,5)->state('available')->create();
        static::$user->attachRole("user");
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAsAnonymousUserICannotLikeAMovie()
    {
        $movie = static::$movies->first();
        $response = $this->post(route('movies.like.store',$movie->id));

        $response->assertStatus(403);
    }

    public function testAsUserICanLikeAMovie()
    {
        $movie = static::$movies->first();
        $response = $this->actingAs(static::$user)
            ->post(route('movies.like.store', $movie->id));

        $response->assertOk();
        $this->assertTrue($movie->likes()->where('user_id',static::$user->id)->exists());

    }

    public function testAUserCannotLikeAMovieTwice()
    {
       $movie = factory(Movie::class)->state('available')->create();
        Like::create([
           'movie_id' => $movie->id,
           'user_id' => static::$user->id,
            'created_at' => now()
       ]);

       $response = $this->actingAs(static::$user)
           ->post(route('movies.like.store', $movie->id));

       $response->assertStatus(400);

       $response->assertExactJson([
           'message' =>  'Already liked this movie'
       ]);
    }

    public function testAUserCanRemoveALike()
    {
        $movie = factory(Movie::class)->state('available')->create();
        Like::create([
            'movie_id' => $movie->id,
            'user_id' => static::$user->id,
            'created_at' => now()
        ]);

        $response = $this->actingAs(static::$user)
            ->delete(route('movies.like.destroy', $movie->id));

        $response->assertStatus(204);

    }
}
