<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    public function test_it_requires_an_email()
    {
        $this->json('POST', 'api/auth/login')
            ->assertJsonValidationErrors(['email']);
    }

    public function test_it_requires_a_password()
    {
        $this->json('POST', 'api/auth/login')
            ->assertJsonValidationErrors(['password']);
    }

    public function test_it_returns_a_mismatch_error_if_credentials_do_not_exist()
    {
        $this->json('POST', 'api/auth/login', [
            'email' => 'abc',
            'password' => 'secrets'
        ])
            ->assertJsonValidationErrors(['message']);
    }

    public function test_it_returns_a_token_if_credentials_do_match()
    {
        $user = User::factory()->create(['password' => 'secret']);

        $this->json('POST', 'api/auth/login', [
            'email' => $user->email,
            'password' => 'secret'
        ])
            ->assertJsonStructure([
                'meta' => [
                    'token'
                ]
            ]);
    }

    public function test_it_returns_a_user_if_credentials_do_match()
    {
        $user = User::factory()->create(['password' => 'secret']);

        $this->json('POST', 'api/auth/login', [
            'email' => $user->email,
            'password' => 'secret'
        ])
            ->assertJsonFragment([
                'email' => $user->email
            ]);
    }
}
