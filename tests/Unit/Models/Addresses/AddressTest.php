<?php

namespace Tests\Unit\Models\Addresses;

use Tests\TestCase;
use App\Models\User;
use App\Models\Address;
use App\Models\Country;

class AddressTest extends TestCase
{
    public function test_it_has_a_country()
    {
        $address = Address::factory()->create([
            'user_id' => User::factory()->create()->id
        ]);

        $this->assertInstanceOf(Country::class, $address->country);
    }

    public function test_it_belongs_to_a_user()
    {
        $address = Address::factory()->create([
            'user_id' => User::factory()->create()->id
        ]);

        $this->assertInstanceOf(User::class, $address->user);
    }

    public function test_it_sets_old_addresses_to_not_default_when_creating_new_one()
    {
        $user = User::factory()->create();

        $oldAddress = Address::factory()->create([
            'default' => true,
            'user_id' => $user->id
        ]);

        Address::factory()->create([
            'user_id' => $user->id
        ]);

        $this->assertFalse($oldAddress->fresh()->default);
    }
}
