<?php

namespace App\Events\Orders;

use App\Models\Order;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class OrderPaymentFailed
{
    use Dispatchable,  SerializesModels;

    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }
}
