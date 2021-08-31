<?php

namespace App\Cart\Payments\Gateways;

use App\Models\PaymentMethod;
use App\Cart\Payments\GatewayCustomer;

class StripeGatewayCustomer implements GatewayCustomer
{
    public function charge(PaymentMethod $card, $amount)
    {
    }

    public function addCard($token)
    {
        dd('Add card');
    }
}
