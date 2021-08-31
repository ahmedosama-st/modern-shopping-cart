<?php

namespace App\Cart\Payments\Gateways;

use App\Models\User;
use App\Cart\Payments\Gateway;
use Stripe\Customer as StripeCustomer;

class StripeGateway implements Gateway
{
    protected User $user;

    public function withUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    public function getCustomer()
    {
        return new StripeGatewayCustomer(
            $this,
            StripeCustomer::retrieve($this->user->gateway_customer_id)
        );
    }

    public function user()
    {
        return $this->user;
    }

    public function createCustomer()
    {
        if ($this->user->gateway_customer_id) {
            return $this->getCustomer();
        }

        $customer = new StripeGatewayCustomer(
            $this,
            $this->createStripeCustomer()
        );

        $this->user->update([
            'gateway_customer_id' => $customer->id()
        ]);

        return $customer;
    }

    protected function createStripeCustomer()
    {
        return StripeCustomer::create([
            'email' => $this->user->email
        ]);
    }
}
