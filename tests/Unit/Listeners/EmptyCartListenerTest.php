<?php

namespace Tests\Unit\Listeners;

use App\Cart\Cart;
use Tests\TestCase;
use App\Models\User;
use App\Models\ProductVariation;
use App\Listeners\Orders\EmptyCart;

class EmptyCartListenerTest extends TestCase
{
    public function test_it_should_clear_the_cart()
    {
        $cart = new Cart(
            $user = User::factory()->create()
        );

        $user->cart()->attach(
            ProductVariation::factory()->create()
        );

        $listener = new EmptyCart($cart);

        $listener->handle();

        $this->assertEmpty($user->cart);
    }
}
