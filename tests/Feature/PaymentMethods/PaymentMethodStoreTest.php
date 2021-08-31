<?php

namespace Tests\Feature\PaymentMethods;

use Tests\TestCase;
use App\Models\User;

class PaymentMethodStoreTest extends TestCase
{
    public function test_it_fails_if_authenticated()
    {
        $this->json('POST', 'api/payment-methods')
            ->assertStatus(401);
    }

    public function test_it_requires_a_token()
    {
        $user = User::factory()->create();

        $this->jsonAs($user, 'POST', 'api/payment-methods')
            ->assertJsonValidationErrors(['token']);
    }

    public function test_it_can_sucessfully_add_a_card()
    {
        $user = User::factory()->create();

        $this->jsonAs($user, 'POST', 'api/payment-methods', [
            'token' => 'tok_visa'
        ]);

        $this->assertDatabaseHas('payment_methods', [
            'user_id' => $user->id,
            'card_type' => 'Visa',
            'last_four' => '4242'
        ]);
    }

    public function test_it_returns_created_card()
    {
        $user = User::factory()->create();

        $this->jsonAs($user, 'POST', 'api/payment-methods', [
            'token' => 'tok_visa'
        ])->assertJsonFragment([
            'card_type' => 'Visa'
        ]);
    }

    public function test_it_sets_the_created_card_as_default()
    {
        $user = User::factory()->create();

        $response = $this->jsonAs($user, 'POST', 'api/payment-methods', [
            'token' => 'tok_visa'
        ]);

        $this->assertDatabaseHas('payment_methods', [
            'id' => json_decode($response->getContent())->data->id,
            'default' => true
        ]);
    }
}
