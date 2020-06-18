<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testJwtIdentifierReturnsUserId()
    {
        /**
         * @var User $user
         */
        $user = factory(User::class)->create();

        $this->assertEquals($user->getJWTIdentifier(),$user->getKey());
    }

    public function testJWTCustomClaimsReturnsEmptyArray(){
        /**
         * @var User $user
         */
        $user = factory(User::class)->create();

        $this->assertEquals($user->getJWTCustomClaims(),[]);

    }
}
