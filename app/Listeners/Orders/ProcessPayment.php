<?php

namespace App\Listeners\Orders;

use App\Cart\Payments\Gateway;
use App\Events\Orders\OrderPaid;
use App\Events\Orders\OrderCreated;
use App\Events\Orders\OrderPaymentFailed;
use App\Exceptions\PaymentFailedException;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessPayment implements ShouldQueue
{
    protected Gateway $gateway;

    public function __construct(Gateway $gateway)
    {
        $this->gateway = $gateway;
    }

    public function handle(OrderCreated $event)
    {
        $order = $event->order;

        try {
            $this->gateway->withUser($order->user)
                ->getCustomer()
                ->charge(
                    $order->paymentMethod,
                    $order->total()->amount()
                );

            event(new OrderPaid($order));
        } catch (PaymentFailedException) {
            event(new OrderPaymentFailed($order));
        }
    }
}
