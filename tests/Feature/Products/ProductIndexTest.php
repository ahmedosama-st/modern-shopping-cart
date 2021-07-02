<?php

namespace Tests\Feature\Products;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Foundation\Testing\{RefreshDatabase, WithFaker};

class ProductIndexTest extends TestCase
{
    public function test_it_shows_a_collection_of_products()
    {
        $product = Product::factory()->create();

        $this->json('GET', 'api/products')
            ->assertJsonFragment([
                'id' => $product->id
            ]);
    }

    public function test_it_has_paginated_data()
    {
        $this->json('GET', 'api/products')
            ->assertJsonStructure([
                'links'
            ]);
    }
}
