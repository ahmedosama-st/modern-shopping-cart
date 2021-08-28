<?php

namespace Tests\Feature\Cart;

use Tests\TestCase;
use App\Models\User;
use App\Models\ProductVariation;

class CartUpdateTest extends TestCase
{
    public function test_it_fails_if_unauthenticated()
    {
        $this->json('PATCH', 'api/cart/1')
            ->assertStatus(401);
    }

    public function test_it_fails_if_product_cannot_be_found()
    {
        $user = User::factory()->create();

        $this->jsonAs($user, 'PATCH', 'api/cart/1')->assertStatus(404);
    }

    public function test_it_fails_quantity_not_provided()
    {
        $user = User::factory()->create();

        $product = ProductVariation::factory()->create();

        $this->jsonAs($user, 'PATCH', "api/cart/{$product->id}")->assertJsonValidationErrors(['quantity']);
    }

    public function test_it_requires_a_quantity_of_one_or_more()
    {
        $user = User::factory()->create();

        $product = ProductVariation::factory()->create();

        $this->jsonAs(
            $user,
            'PATCH',
            "api/cart/{$product->id}",
            ['quantity' => 0]
        )
            ->assertJsonValidationErrors(['quantity']);
    }

    public function test_it_updates_quantity_of_a_product()
    {
        $user = User::factory()->create();

        $user->cart()->attach(
            $product = ProductVariation::factory()->create(),
            [
                'quantity' => 3
            ]
        );

        $this->jsonAs(
            $user,
            'PATCH',
            "api/cart/{$product->id}",
            ['quantity' => $quantity = 6]
        );

        $this->assertDatabaseHas('cart_user', [
            'product_variation_id' => $product->id,
            'quantity' => $quantity
        ]);
    }
}
