<?php

namespace App\Listeners\Orders;

class CreateTransaction
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $event->order->transactions()->create([
            'total' => $event->order->total()->amount()
        ]);
    }
}
