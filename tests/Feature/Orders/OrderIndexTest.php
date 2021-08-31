<?php

namespace Tests\Feature\Orders;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;

class OrderIndexTest extends TestCase
{
    public function test_it_fails_if_user_is_unauthenticated()
    {
        $this->json('GET', 'api/orders')
            ->assertStatus(401);
    }

    public function test_it_returns_a_collection_of_orders()
    {
        $user = User::factory()->create();

        $order = Order::factory()->create([
            'user_id' => $user->id
        ]);

        $this->jsonAs($user, 'GET', 'api/orders')
            ->assertJsonFragment([
                'id' => $order->id
            ]);
    }

    public function test_it_orders_by_latest_first()
    {
        $user = User::factory()->create();

        $order = Order::factory()->create([
            'user_id' => $user->id
        ]);

        $anotherOrder = Order::factory()->create([
            'user_id' => $user->id,
            'created_at' => now()->subDay()
        ]);

        $this->jsonAs($user, 'GET', 'api/orders')
            ->assertSeeInOrder([
                $order->created_at->toDateTimeString(),
                $anotherOrder->created_at->toDateTimeString()
            ]);
    }

    public function test_it_has_pagination()
    {
        $user = User::factory()->create();

        $order = Order::factory()->create([
            'user_id' => $user->id
        ]);

        $this->jsonAs($user, 'GET', 'api/orders')
            ->assertJsonStructure([
                'links', 'meta'
            ]);
    }
}
