<?php

namespace Tests\Feature\Addresses;

use Tests\TestCase;
use App\Models\User;
use App\Models\Address;

class AddressIndexTest extends TestCase
{
    public function test_it_fails_if_unauthenticated()
    {
        $this->json('GET', 'api/addresses')->assertStatus(401);
    }

    public function test_it_shows_addresses()
    {
        $user = User::factory()->create();

        $address = Address::factory()->create([
            'user_id' => $user->id
        ]);

        $this->jsonAs($user, 'GET', 'api/addresses')
            ->assertJsonFragment([
                'id' => $address->id
            ]);
    }
}
