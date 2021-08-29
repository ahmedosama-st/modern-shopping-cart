<?php

namespace Database\Factories;

use App\Models\ShippingMethods;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShippingMethodsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ShippingMethods::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => 'Aramax',
            'price' => 1000
        ];
    }
}
