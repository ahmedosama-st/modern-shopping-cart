<?php

namespace Tests\Feature\Addresses;

use Tests\TestCase;
use App\Models\User;
use App\Models\Address;
use App\Models\Country;
use App\Models\ShippingMethod;

class AddressShippingTest extends TestCase
{
    public function test_it_fails_if_the_user_is_not_authenticated()
    {
        $this->json('GET', 'api/addresses/1/shipping')
            ->assertStatus(401);
    }

    public function test_it_fails_if_address_cannot_be_found()
    {
        $user = User::factory()->create();

        $this->jsonAs($user, 'GET', 'api/addresses/1/shipping')
            ->assertStatus(404);
    }

    public function test_it_fails_if_the_user_does_not_own_the_address()
    {
        $user = User::factory()->create();

        $address = Address::factory()->create([
            'user_id' => User::factory()->create()->id
        ]);

        $this->jsonAs($user, 'GET', "api/addresses/{$address->id}/shipping")
            ->assertStatus(403);
    }

    public function test_it_it_shows_shipping_methods_for_the_address()
    {
        $user = User::factory()->create();
        $country = Country::factory()->create();

        $address = Address::factory()->create([
            'user_id' => $user->id,
            'country_id' => $country->id
        ]);

        $country->shippingMethods()->save(
            $shipping = ShippingMethod::factory()->create()
        );

        $this->jsonAs($user, 'GET', "api/addresses/{$address->id}/shipping")
            ->assertJsonFragment([
                'name' => $shipping->name
            ]);
    }
}
