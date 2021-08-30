<?php

namespace Tests\Unit\Cart;

use App\Cart\Cart;
use App\Cart\Money;
use Tests\TestCase;
use App\Models\User;
use App\Models\ShippingMethod;
use App\Models\ProductVariation;

class CartTest extends TestCase
{
    public function test_it_can_add_products_to_cart()
    {
        $cart = new Cart(
            $user = User::factory()->create()
        );

        $product = ProductVariation::factory()->create();

        $cart->add([
            ['id' => $product->id, 'quantity' => 1]
        ]);

        $this->assertCount(1, $user->fresh()->cart);
    }

    public function test_it_increments_quantity_when_adding_more_products()
    {
        $product = ProductVariation::factory()->create();

        $cart = new Cart(
            $user = User::factory()->create()
        );

        $cart->add([
            ['id' => $product->id, 'quantity' => 1]
        ]);

        $cart = new Cart($user->fresh());

        $cart->add([
            ['id' => $product->id, 'quantity' => 1]
        ]);

        $this->assertEquals($user->fresh()->cart->first()->pivot->quantity, 2);
    }

    public function test_it_can_update_existing_products_in_cart()
    {
        $cart = new Cart(
            $user = User::factory()->create()
        );

        $user->cart()->attach(
            $product = ProductVariation::factory()->create(),
            [
                'quantity' => 1
            ]
        );

        $cart->update($product->id, 2);

        $this->assertEquals($user->fresh()->cart->first()->pivot->quantity, 2);
    }

    public function test_it_can_delete_a_product_from_the_cart()
    {
        $cart = new Cart(
            $user = User::factory()->create()
        );

        $user->cart()->attach(
            $product = ProductVariation::factory()->create(),
            [
                'quantity' => 2
            ]
        );

        $cart->delete($product->id);

        $this->assertCount(0, $user->fresh()->cart);
    }

    public function test_it_can_empty_the_cart()
    {
        $cart = new Cart(
            $user = User::factory()->create()
        );

        $user->cart()->attach(
            ProductVariation::factory()->create()
        );

        $cart->empty();

        $this->assertCount(0, $user->fresh()->cart);
    }

    public function test_it_can_check_if_the_cart_is_empty()
    {
        $cart = new Cart(
            $user = User::factory()->create()
        );

        $user->cart()->attach(
            $product = ProductVariation::factory()->create(),
            [
                'quantity' => 0
            ]
        );

        $this->assertTrue($cart->isEmpty());
    }

    public function test_it_returns_a_money_instance_for_subtotal()
    {
        $cart = new Cart(
            $user = User::factory()->create()
        );

        $this->assertInstanceOf(Money::class, $cart->subtotal());
    }

    public function test_it_gets_the_cart_subtotal_correctly()
    {
        $cart = new Cart(
            $user = User::factory()->create()
        );

        $user->cart()->attach(
            $product = ProductVariation::factory()->create([
                'price' => 1000
            ]),
            [
                'quantity' => 2
            ]
        );

        $this->assertEquals($cart->subtotal()->amount(), 2000);
    }

    public function test_it_returns_a_money_instance_for_total()
    {
        $cart = new Cart(
            User::factory()->create()
        );

        $this->assertInstanceOf(Money::class, $cart->total());
    }

    public function test_it_syncs_the_cart_to_update_quantities()
    {
        $cart = new Cart(
            $user = User::factory()->create()
        );

        $user->cart()->attach(
            ProductVariation::factory()->create(),
            [
                'quantity' => 2
            ]
        );

        $cart->sync();

        $this->assertEquals($user->fresh()->cart->first()->pivot->quantity, 0);
    }

    public function test_it_can_check_if_the_cart_has_changed_after_syncing()
    {
        $cart = new Cart(
            $user = User::factory()->create()
        );

        $user->cart()->attach(
            ProductVariation::factory()->create(),
            [
                'quantity' => 2
            ]
        );

        $cart->sync();

        $this->assertTrue($cart->hasChanged());
    }

    public function test_it_can_return_the_correct_total_without_shipping()
    {
        $cart = new Cart(
            $user = User::factory()->create()
        );

        $user->cart()->attach(
            ProductVariation::factory()->create(['price' => 1000]),
            [
                'quantity' => 2
            ]
        );

        $this->assertEquals($cart->total()->amount(), 2000);
    }

    public function test_it_can_return_the_correct_total_with_shipping()
    {
        $cart = new Cart(
            $user = User::factory()->create()
        );

        $shipping = ShippingMethod::factory()->create(['price' => 1000]);

        $user->cart()->attach(
            ProductVariation::factory()->create(['price' => 1000]),
            [
                'quantity' => 2
            ]
        );

        $this->assertEquals($cart->withShipping($shipping->id)->total()->amount(), 3000);
    }
}
