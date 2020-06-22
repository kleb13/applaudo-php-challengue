<?php

namespace Tests\Feature;

use App\Models\Movie;
use App\Models\Rental;
use App\Models\User;
use App\Scopes\AvailabilityScope;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Ramsey\Collection\Collection;
use Tests\TestCase;

class UserMovieControllerTest extends TestCase
{

    use DatabaseMigrations;
    /**
     * @var Collection
     */
    static $movies;
    static $moviesUnavailable;
    /**
     * @var User
     */
    static $admin;
    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
        static::$admin = factory(User::class)->create();
        static::$admin->attachRole("user");
        static::$movies = factory(Movie::class,15)->state('available')->create();
        static::$moviesUnavailable = factory(Movie::class,15)->state('unavailable')->create();
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAsAUserICannotSeeDetailForUnavailableMovies()
    {
        $unavailable = factory(Movie::class)->state('unavailable')->create();

        $response = $this->actingAs(static::$admin)
            ->get(route('movies.show',$unavailable->id));

        $response->assertNotFound();

    }

    public function testAsNormalUserICanSeeOnlyAvailableMovies()
    {

        $response = $this->actingAs(static::$admin)
            ->get(route('movies.index'));
        $response->assertOk();
        $response->assertJsonCount(10,'data');
        $response->assertJsonFragment([
            'total' => static::$movies->count()
        ]);

    }

    public function testAsNormalUserICanBuyAMovieWithStockGreaterThanZero()
    {
        $movie = factory(Movie::class)->state('available')->create([
            'stock' => 1
        ]);
        $response = $this->actingAs(static::$admin)
            ->post(route('movie.buy',$movie->id));

        $response->assertOk();
        $response->assertExactJson([
            'message' => 'Ok'
        ]);
        $this->assertEquals(Movie::withoutGlobalScope(AvailabilityScope::class)->find($movie->id)->stock,0);

    }

    public function testAsNormalUserICannotBuyAMovieWithStockGEqualZero()
    {
        $movie = factory(Movie::class)->state('available')->create([
            'stock' => 0
        ]);
        $response = $this->actingAs(static::$admin)
            ->post(route('movie.buy',$movie->id));

        $response->assertStatus(400);
        $response->assertExactJson([
            'message' => "Not enough stock"
        ]);

    }

    public function testAsNormalUserICanRentAMovieWithStock()
    {
        $movie = factory(Movie::class)->state('available')->create([
            'stock' => 1
        ]);
        $response = $this->actingAs(static::$admin)
            ->post(route('movie.rent',$movie->id));

        $response->assertOk();
        $response->assertExactJson([
            'message' => 'Ok'
        ]);
        $this->assertEquals(Movie::withoutGlobalScope(AvailabilityScope::class)->find($movie->id)->stock,0);

    }

    public function testAsNormalUserICanRentTheSameMovieTwice()
    {
        $movie = factory(Movie::class)->state('available')->create([
            'stock' => 3
        ]);
        Rental::create([
            'user_id' => static::$admin->id,
            'created_at' => now(),
            'expected_return_date' => now()->addWeeks(2),
            'movie_id' => $movie->id
        ]);
        $response = $this->actingAs(static::$admin)
            ->post(route('movie.rent',$movie->id));

        $response->assertStatus(400);
        $response->assertExactJson([
            'message' => "This movie is already rented"
        ]);
    }

    public function testAsNormalUserIAmPenalizedForReturnigBackLate()
    {
        $movie = factory(Movie::class)->state('unavailable')->create([
            'stock' => 0
        ]);
        Rental::create([
            'user_id' => static::$admin->id,
            'created_at' => now(),
            'expected_return_date' => now()->addWeeks(-3)->format('Y-m-d'),
            'movie_id' => $movie->id
        ]);
        $response = $this->actingAs(static::$admin)
            ->post(route('movie.return',$movie->id));

        $response->assertExactJson([
            'message' => "You were penalized for late return"
        ]);
        $response->assertOk();

        $this->assertEquals(Movie::withoutGlobalScope(AvailabilityScope::class)->find($movie->id)->stock,1);
    }
}
