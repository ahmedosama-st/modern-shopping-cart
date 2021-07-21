<?php

namespace Tests\Unit\Models\Users;

use Tests\TestCase;
use App\Models\User;
use App\Models\ProductVariation;

class UserTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_hashes_password_on_creating()
    {
        $user = User::factory()->create([
            'password' => 'secret'
        ]);

        $this->assertNotEquals($user->password, 'secret');
    }

    public function test_it_has_many_cart_products()
    {
        $user = User::factory()->create();

        $user->cart()->attach(
            ProductVariation::factory()->create()
        );

        $this->assertInstanceOf(ProductVariation::class, $user->cart->first());
    }

    public function test_it_has_a_quantity_for_each_cart_product()
    {
        $user = User::factory()->create();

        $user->cart()->attach(
            ProductVariation::factory()->create(),
            [
                'quantity' => $quantity = 5
            ]
        );

        $this->assertEquals($user->cart->first()->pivot->quantity, $quantity);
    }
}
