<?php

namespace Tests\Unit\Listeners;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Events\Orders\OrderPaid;
use App\Listeners\Orders\MarkOrderProcessing;

class MarkOrderProcessingListenerTest extends TestCase
{
    public function test_it_should_clear_the_cart()
    {
        $event = new OrderPaid(
            $order = Order::factory()->create([
                'user_id' => User::factory()->create()->id
            ])
        );

        $listener = new MarkOrderProcessing($event);

        $listener->handle($event);

        $this->assertEquals($order->fresh()->status, Order::PROCESSING);
    }
}
