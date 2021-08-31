<?php

namespace App\Listeners\Orders;

use App\Cart\Cart;

class EmptyCart
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(protected Cart $cart)
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle()
    {
        $this->cart->empty();
    }
}
