<?php

namespace Tests\Unit\Products;

use Tests\TestCase;
use App\Models\{Product, ProductVariation, ProductVariationType};

class ProductVariationTest extends TestCase
{
    public function test_it_has_one_variation_type()
    {
        $variation = ProductVariation::factory()->create();

        $this->assertInstanceOf(ProductVariationType::class, $variation->type);
    }

    public function test_it_belongs_to_a_product()
    {
        $variation = ProductVariation::factory()->create();

        $this->assertInstanceOf(Product::class, $variation->product);
    }
}
