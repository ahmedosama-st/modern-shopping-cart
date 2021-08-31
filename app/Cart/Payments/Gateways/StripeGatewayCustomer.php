<?php

namespace App\Cart\Payments\Gateways;

use App\Models\PaymentMethod;
use App\Cart\Payments\Gateway;
use Stripe\Charge as StripeCharge;
use App\Cart\Payments\GatewayCustomer;
use Stripe\Customer as StripeCustomer;
use App\Exceptions\PaymentFailedException;

class StripeGatewayCustomer implements GatewayCustomer
{
    protected Gateway $gateway;

    protected StripeCustomer $customer;

    public function __construct(Gateway $gateway, StripeCustomer $customer)
    {
        $this->gateway = $gateway;
        $this->customer = $customer;
    }

    public function charge(PaymentMethod $card, $amount)
    {
        try {
            StripeCharge::create([
                'currency' => 'gbp',
                'amount' => $amount,
                'customer' => $this->customer->id,
                'source' => $card->provider_id
            ]);
        } catch (\Exception) {
            throw new PaymentFailedException();
        }
    }

    public function addCard($token)
    {
        $card = $this->customer->sources->create([
            'source' => $token
        ]);

        $this->customer->default_source = $card->id;
        $this->customer->save();

        return $this->gateway->user()->paymentMethods()->create([
            'provider_id' => $card->id,
            'card_type' => $card->brand,
            'last_four' => $card->last4,
            'default' => true
        ]);
    }

    public function id()
    {
        return $this->customer->id;
    }
}
