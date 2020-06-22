<?php

namespace Tests\Feature;

use App\Models\Movie;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Ramsey\Collection\Collection;
use Tests\TestCase;

class AdminMovieControllerTest extends TestCase
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
        static::$movies = factory(Movie::class,15)->state('available')->create();
        static::$moviesUnavailable = factory(Movie::class,15)->state('unavailable')->create();
        static::$admin->attachRole("admin");
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAsAnonymousUserIReceivedNotAuthorized()
    {
        $response = $this->get('/api/v1/admin/movies');

        $response->assertStatus(403);
    }

    public function testAsAdminIGetTheMoviesPaginated()
    {

        $response = $this->actingAs(static::$admin)
            ->get('/api/v1/admin/movies');

        $response->assertOk();
        $response->assertJsonCount(10,'data');
        $response->assertJsonFragment([
                'total' => 30
        ]);
    }

    public function testAsAdminICanFilterByAvailable()
    {
        $response = $this->actingAs(static::$admin)
            ->get('/api/v1/admin/movies?filter=available');
        $response->assertOk();
        $response->assertJsonCount(10,'data');
        $response->assertJsonFragment([
            'total' => static::$movies->count()
        ]);
        $response->assertJsonFragment([
            'availability' => "1"
        ]);
    }

    public function testAsAdminICanFilterByUnavailable()
    {
        $response = $this->actingAs(static::$admin)
            ->get('/api/v1/admin/movies?filter=unavailable');
        $response->assertOk();
        $response->assertJsonCount(10,'data');
        $response->assertJsonFragment([
            'total' => static::$moviesUnavailable->count()
        ]);
        $response->assertJsonFragment([
            'availability' => "0"
        ]);
    }

    public function testCanSeeDetailOfAvailableMovie()
    {
        $available = static::$movies->first();

        $response = $this->actingAs(static::$admin)
            ->get('/api/v1/admin/movies/'.$available->id);

        $response->assertOk();

        $response->assertJsonFragment([
            'id' =>  $available->id,
            'availability' => "1",
            'title' => $available->title
        ]);
    }

    public function testAsAdminICanDeleteAMovie()
    {
        $movie = factory(Movie::class)->create();
        $response = $this->actingAs(static::$admin)
            ->delete('/api/v1/admin/movies/'.$movie->id);

        $response->assertNoContent();

        $this->assertFalse(Movie::where('id',$movie->id)->exists());
    }

    public function testAsAdminICanUpdateAMovie()
    {
        $movie = factory(Movie::class)->create();
        $response = $this->actingAs(static::$admin)
            ->withHeader('Accept','application/json')
            ->put('/api/v1/admin/movies/'.$movie->id,[
                'stock' => $movie->stock,
                'sale_price' =>$movie->sale_price,
                'rental_price' => $movie->rental_price,
                'availability' => true,
                'title' => 'my title',
                'description' => 'description'
            ]);
        $response->assertJsonFragment([
            'description' => 'description'
        ]);
    }
}
