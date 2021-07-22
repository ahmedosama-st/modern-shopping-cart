<?php

namespace Tests\Feature\Categories;

use Tests\TestCase;
use App\Models\Category;
use Illuminate\Foundation\Testing\{RefreshDatabase, WithFaker};

class CategoryIndexTest extends TestCase
{
    public function test_it_returns_a_collection_of_categories()
    {
        $categories = Category::factory()->count(2)->create();

        $this->json('GET', 'api/categories')
            ->assertJsonFragment([
                'slug' => $categories->get(0)->slug,
            ], [
                'slug' => $categories->get(1)->slug,
            ]);
    }

    public function test_it_retunrs_only_parent_categories()
    {
        $category = Category::factory()->create();

        $category->children()->save(
            Category::factory()->make()
        );

        $this->json('GET', 'api/categories')->assertJsonCount(1, 'data');
    }

    public function test_it_returns_ordered_categories()
    {
        $category = Category::factory()->create([
            'order' => 2
        ]);

        $anotherCategory = Category::factory()->create([
            'order' => 1
        ]);

        $this->json('GET', 'api/categories')->assertSeeInOrder([
            $anotherCategory->slug,
            $category->slug
        ]);
    }
}
