<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateAdminCommandTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCreateAAdminWithTheValidData()
    {
        $this->artisan('app:create-admin')
            ->expectsQuestion('Admin Email:','test@test.com')
            ->expectsQuestion('Admin Name:','test')
            ->expectsQuestion('Admin Password:','password')
            ->expectsQuestion("Confirm Password:",'password')
            ->assertExitCode(0);
    }

    public function testCreateAAdminFailsWithWrongPassword()
    {
        $this->artisan('app:create-admin')
            ->expectsQuestion('Admin Email:','test@test.com')
            ->expectsQuestion('Admin Name:','test')
            ->expectsQuestion('Admin Password:','password')
            ->expectsQuestion("Confirm Password:",'passwor')
            ->assertExitCode(1);
    }
}
