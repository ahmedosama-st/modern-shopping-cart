<?php

namespace Database\Factories;

use App\Models\ProductVariationType;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductVariationTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductVariationType::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->name
        ];
    }
}
