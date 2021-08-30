<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Address;
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
            'shipping_method_id' => ShippingMethod::factory()->create()->id
        ];
    }
}
