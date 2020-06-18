<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var User
     */
    static $user;
    public function setUp(): void
    {
        parent::setUp();
        static::$user = factory(User::class)->create();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testLoginReturnTheCorrectJsonStructureAndStatus()
    {
        $response = $this->post('/api/v1/auth/login',[
            'email' => static::$user->email,
            'password' => 'password'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            "access_token",
            "token_type",
            "expires_in"
        ]);
    }

    public function testLoginFailureReturnTheCorrectJsonAndStatus()
    {
        $response = $this->post('/api/v1/auth/login', [
            'email' => static::$user->email,
            'password' => 'wrong_password'
        ]);

        $response->assertStatus(401);
        $response->assertJson([
            "error" => 'Unauthorized',
        ]);
    }
}
