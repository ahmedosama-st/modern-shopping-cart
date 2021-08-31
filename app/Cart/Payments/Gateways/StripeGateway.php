<?php

namespace App\Cart\Payments\Gateways;

use App\Models\User;
use App\Cart\Payments\Gateway;

class StripeGateway implements Gateway
{
    protected User $user;

    public function withUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    public function createCustomer()
    {
        if ($this->user->gateway_customer_id) {
            return 'customer';
        }

        return new StripeGatewayCustomer();
    }
}
