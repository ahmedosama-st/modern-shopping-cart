<?php

namespace Tests\Unit\Models\Categories;

use Tests\TestCase;
use App\Models\{Category, Product};

class CategoryTest extends TestCase
{
    public function test_it_has_many_children()
    {
        $category = Category::factory()->create();

        $category->children()->save(
            Category::factory()->create()
        );

        $this->assertInstanceOf(Category::class, $category->children->first());
    }

    public function test_it_is_orderable()
    {
        $category = Category::factory()->create([
            'order' => 2
        ]);

        $anotherCategory = $category->children()->save(
            Category::factory()->create([
                'order' => 1
            ])
        );

        $this->assertEquals($anotherCategory->name, Category::ordered()->first()->name);
    }

    public function test_it_can_fetch_only_parents()
    {
        $category = Category::factory()->create();

        $category->children()->save(
            Category::factory()->create()
        );

        $this->assertEquals(1, Category::parents()->count());
    }

    public function test_it_has_many_products()
    {
        $category = Category::factory()->create();

        $category->products()->save(
            Product::factory()->make()
        );

        $this->assertInstanceOf(Product::class, $category->products->first());
    }
}
