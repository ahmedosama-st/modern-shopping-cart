<?php

namespace Tests\Unit\Models\Orders;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Address;
use App\Models\ShippingMethod;

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

    public function test_it_belongs_to_a_shipping_method()
    {
        $order = Order::factory()->create([
            'user_id' => User::factory()->create()->id
        ]);

        $this->assertInstanceOf(ShippingMethod::class, $order->shippingMethod);
    }
}
