<?php

namespace Tests\Unit\Models\Shipping;

use App\Cart\Money;
use Tests\TestCase;
use App\Models\ShippingMethods;

class ShippingMethodsTest extends TestCase
{
    public function test_it_returns_a_money_instance_for_the_price()
    {
        $shipping = ShippingMethods::factory()->create();

        $this->assertInstanceOf(Money::class, $shipping->price);
    }

    public function test_it_returns_a_formatted_price()
    {
        $shipping = ShippingMethods::factory()->create([
            'price' => 0
        ]);

        $this->assertEquals($shipping->formattedPrice, 'EGP 0.00');
    }
}
