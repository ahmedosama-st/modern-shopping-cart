<?php

namespace Tests\Feature\Cart;

use Tests\TestCase;
use App\Models\User;
use App\Models\ProductVariation;

class CartIndexTest extends TestCase
{
    public function test_it_fails_if_user_is_unauthenticated()
    {
        $this->json('DELETE', 'api/cart/1')->assertStatus(401);
    }

    public function test_it_shows_products_in_the_user_cart()
    {
        $user = User::factory()->create();

        $user->cart()->sync(
            $product = ProductVariation::factory()->create()
        );

        $this->jsonAs($user, 'GET', 'api/cart/')->assertJsonFragment([
            'id' => $product->id,
        ]);
    }
}
