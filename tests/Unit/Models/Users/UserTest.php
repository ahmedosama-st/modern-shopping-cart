<?php

namespace Tests\Unit\Models\Users;

use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_hashes_password_on_creating()
    {
        $user = User::factory()->create([
            'password' => 'secret'
        ]);

        $this->assertNotEquals($user->password, 'secret');
    }
}
