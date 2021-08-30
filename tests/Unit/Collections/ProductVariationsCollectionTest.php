<?php

namespace Tests\Unit\Collections;

use App\Models\Collections\ProductVariationCollection;
use Tests\TestCase;
use App\Models\User;
use App\Models\ProductVariation;

class ProductVariationsCollectionTest extends TestCase
{
    public function test_it_can_get_a_syncing_array()
    {
        $user = User::factory()->create();

        $user->cart()->attach(
            $product = ProductVariation::factory()->create(),
            [
                'quantity' => $quantity = 2
            ]
        );

        $collection = new ProductVariationCollection($user->cart);

        $this->assertEquals($collection->forSyncing(), [
            $product->id => [
                'quantity' => $quantity
            ]
        ]);
    }
}
