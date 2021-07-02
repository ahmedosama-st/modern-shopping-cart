<?php

namespace Tests\Feature\Products;

use Tests\TestCase;
use App\Models\{Category, Product};

class ProductFilteringTest extends TestCase
{
    public function test_it_can_filter_by_category()
    {
        $product = Product::factory()->create();

        $product->categories()->save(
            $category = Category::factory()->create()
        );

        $anotherProduct = Product::factory()->create();

        $this->json('GET', "api/products?category={$category->slug}")
            ->assertJsonCount(1, 'data');
    }
}
