<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Order;
use App\Models\Address;
use App\Models\PaymentMethod;
use App\Models\ShippingMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'address_id' => Address::factory()->create()->id,
            'shipping_method_id' => ShippingMethod::factory()->create()->id,
            'payment_method_id' => PaymentMethod::factory()->create([
                'user_id' => User::factory()->create()->id
            ])->id,
            'subtotal' => 1000
        ];
    }
}
