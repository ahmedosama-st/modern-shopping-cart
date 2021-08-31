<?php

namespace Tests\Unit\Listeners;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Events\Orders\OrderPaid;
use App\Listeners\Orders\CreateTransaction;

class CreateTransactionListenerTest extends TestCase
{
    public function test_it_creats_a_transaction()
    {
        $event = new OrderPaid(
            $order = Order::factory()->create([
                'user_id' => User::factory()->create()->id
            ])
        );

        $listener = new CreateTransaction();

        $listener->handle($event);

        $this->assertDatabaseHas('transactions', [
            'order_id' => $order->id,
            'total' => $order->total()->amount()
        ]);
    }
}
