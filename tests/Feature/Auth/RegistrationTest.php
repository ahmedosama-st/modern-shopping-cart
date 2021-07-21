<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;

class RegistrationTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_reqiures_a_name()
    {
        $this->json('POST', 'api/auth/register')
            ->assertJsonValidationErrors(['name']);
    }

    public function test_it_reqiures_a_email()
    {
        $this->json('POST', 'api/auth/register')
            ->assertJsonValidationErrors(['email']);
    }

    public function test_it_reqiures_a_valid_email()
    {
        $this->json('POST', 'api/auth/register', [
            'email' => 'not_valid'
        ])
            ->assertJsonValidationErrors(['email']);
    }

    public function test_it_reqiures_a_unique_email()
    {
        $user = User::factory()->create();

        $this->json('POST', 'api/auth/register', [
            'email' => $user->email
        ])
            ->assertJsonValidationErrors(['email']);
    }

    public function test_it_requires_a_password()
    {
        $this->json('POST', 'api/auth/register')->
        assertJsonValidationErrors(['password']);
    }

    public function test_it_registers_a_user()
    {
        $this->json('POST', 'api/auth/register', [
            'name' => 'Ahmed Osama',
            'email' => $email = 'marvelphp5@gmail.com',
            'password' => 'secret'
        ]);

        $this->assertDatabaseHas('users', [
            'email' => $email
        ]);
    }

    public function test_it_returns_a_user_resource_on_registration()
    {
        $this->json('POST', 'api/auth/register', [
            'name' => 'Ahmed Osama',
            'email' => $email = 'marvelphp5@gmail.com',
            'password' => 'secret'
        ])->assertJsonFragment(['email' => $email]);
    }
}
