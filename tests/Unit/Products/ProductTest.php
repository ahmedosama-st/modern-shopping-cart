<?php

namespace Tests\Unit\Products;

use Tests\TestCase;
use App\Models\{Category, Product};

class ProductTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_uses_slug_for_route_key_name()
    {
        $product = new Product();

        $this->assertEquals($product->getRouteKeyName(), 'slug');
    }

    public function test_it_has_many_categories()
    {
        $product = Product::factory()->create();

        $product->categories()->save(
            Category::factory()->create()
        );

        $this->assertInstanceOf(Category::class, $product->categories->first());
    }
}
