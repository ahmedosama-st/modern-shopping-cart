<?php

namespace Tests\Unit\Models\Orders;

use App\Cart\Money;
use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Address;
use App\Models\ShippingMethod;
use App\Models\ProductVariation;

class OrderTest extends TestCase
{
    public function test_it_belongs_to_a_user()
    {
        $order = Order::factory()->create([
            'user_id' => User::factory()->create()->id
        ]);

        $this->assertInstanceOf(User::class, $order->user);
    }

    public function test_it_belongs_to_an_address()
    {
        $order = Order::factory()->create([
            'user_id' => User::factory()->create()->id
        ]);

        $this->assertInstanceOf(Address::class, $order->address);
    }

    public function test_it_has_a_default_status_of_pending()
    {
        $order = Order::factory()->create([
            'user_id' => User::factory()->create()->id
        ]);

        $this->assertEquals($order->status, Order::PENDING);
    }

    public function test_it_belongs_to_a_shipping_method()
    {
        $order = Order::factory()->create([
            'user_id' => User::factory()->create()->id
        ]);

        $this->assertInstanceOf(ShippingMethod::class, $order->shippingMethod);
    }

    public function test_it_has_quantity_attached_to_the_products()
    {
        $order = Order::factory()->create([
            'user_id' => User::factory()->create()->id
        ]);

        $order->products()->attach(
            ProductVariation::factory()->create(),
            [
                'quantity' => $quantity = 5
            ]
        );

        $this->assertEquals($order->products->first()->pivot->quantity, $quantity);
    }

    public function test_it_has_many_products()
    {
        $order = Order::factory()->create([
            'user_id' => User::factory()->create()->id
        ]);

        $order->products()->attach(
            ProductVariation::factory()->create(),
            [
                'quantity' => 1
            ]
        );

        $this->assertInstanceOf(ProductVariation::class, $order->products->first());
    }

    public function test_it_returns_a_money_instance_for_the_subtotal()
    {
        $order = Order::factory()->create([
            'user_id' => User::factory()->create()->id
        ]);

        $this->assertInstanceOf(Money::class, $order->subtotal);
    }
    public function test_it_returns_a_money_instance_for_the_total()
    {
        $order = Order::factory()->create([
            'user_id' => User::factory()->create()->id
        ]);

        $this->assertInstanceOf(Money::class, $order->total());
    }
    public function test_it_returns_formatted_subtotal()
    {
        $order = Order::factory()->create([
            'user_id' => User::factory()->create()->id,
            'subtotal' => 1000
        ]);

        $this->assertEquals($order->subtotal->formatted(), 'EGP 10.00');
    }

    public function test_it_adds_shipping_to_total()
    {
        $order = Order::factory()->create([
            'user_id' => User::factory()->create()->id,
            'subtotal' => 1000,
            'shipping_method_id' => ShippingMethod::factory()->create(['price' => 1000])->id
        ]);

        $this->assertEquals($order->total()->amount(), 2000);
    }
}
