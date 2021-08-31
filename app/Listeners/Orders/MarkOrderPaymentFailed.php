<?php

namespace App\Listeners\Orders;

use App\Models\Order;

class MarkOrderPaymentFailed
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $event->order->update([
            'status' => Order::PAYMENT_FAILED
        ]);
    }
}
