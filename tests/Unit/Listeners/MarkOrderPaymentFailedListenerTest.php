<?php

namespace Tests\Unit\Listeners;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Events\Orders\OrderPaymentFailed;
use App\Listeners\Orders\MarkOrderPaymentFailed;

class MarkOrderPaymentFailedListenerTest extends TestCase
{
    public function test_it_should_clear_the_cart()
    {
        $event = new OrderPaymentFailed(
            $order = Order::factory()->create([
                'user_id' => User::factory()->create()->id
            ])
        );

        $listener = new MarkOrderPaymentFailed($event);

        $listener->handle($event);

        $this->assertEquals($order->fresh()->status, Order::PAYMENT_FAILED);
    }
}
