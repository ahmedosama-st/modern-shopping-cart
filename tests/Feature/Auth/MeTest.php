<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;

class MeTest extends TestCase
{
    public function test_it_fails_if_user_isnt_authenticated()
    {
        $this->json('GET', 'api/auth/me')->assertStatus(401);
    }

    public function test_it_returns_user_details()
    {
        $user = User::factory()->create();

        $this->jsonAs($user, 'GET', 'api/auth/me')->assertJsonFragment([
            'email' => $user->email
        ]);
    }
}
