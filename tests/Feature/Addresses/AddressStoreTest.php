<?php

namespace Tests\Feature\Addresses;

use Tests\TestCase;
use App\Models\User;
use App\Models\Country;

class AddressStoreTest extends TestCase
{
    public function test_it_fails_if_unauthenticated()
    {
        $this->json('POST', 'api/addresses')->assertStatus(401);
    }

    public function test_it_requires_a_name()
    {
        $user = User::factory()->create();

        $this->jsonAs($user, 'POST', 'api/addresses')->assertJsonValidationErrors('name');
    }

    public function test_it_requires_address_line_one()
    {
        $user = User::factory()->create();

        $this->jsonAs($user, 'POST', 'api/addresses')->assertJsonValidationErrors('address_1');
    }

    public function test_it_requires_a_city()
    {
        $user = User::factory()->create();

        $this->jsonAs($user, 'POST', 'api/addresses')->assertJsonValidationErrors('city');
    }

    public function test_it_requires_a_postal_code()
    {
        $user = User::factory()->create();

        $this->jsonAs($user, 'POST', 'api/addresses')->assertJsonValidationErrors('postal_code');
    }

    public function test_it_requires_a_country()
    {
        $user = User::factory()->create();

        $this->jsonAs($user, 'POST', 'api/addresses')->assertJsonValidationErrors('country_id');
    }

    public function test_it_requires_a_valid_country()
    {
        $user = User::factory()->create();

        $this->jsonAs($user, 'POST', 'api/addresses', [
            'country_id' => 1
        ])->assertJsonValidationErrors('country_id');
    }

    public function test_it_stores_an_address()
    {
        $user = User::factory()->create();

        $this->jsonAs($user, 'POST', 'api/addresses', $payload = [
            'name' => 'Ahmed Osama',
            'address_1' => '123 code street',
            'city' => 'Vscode',
            'postal_code' => 'CO1234',
            'country_id' => Country::factory()->create()->id
        ]);

        $this->assertDatabaseHas('addresses', array_merge($payload,  [
            'user_id' => $user->id,
        ]));
    }

    public function test_it_returns_an_address_when_created()
    {
               $user = User::factory()->create();

        $response = $this->jsonAs($user, 'POST', 'api/addresses', $payload = [
            'name' => 'Ahmed Osama',
            'address_1' => '123 code street',
            'city' => 'Vscode',
            'postal_code' => 'CO1234',
            'country_id' => Country::factory()->create()->id
        ]);

        $response->assertJsonFragment([
            'id' => json_decode($response->getContent())->data->id,
            'name' => $payload['name']
        ]);
    }
}
