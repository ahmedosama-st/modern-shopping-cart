<?php

namespace Tests\Unit\Listeners;

use Mockery;
use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Events\Orders\OrderPaid;
use App\Events\Orders\OrderCreated;
use Illuminate\Support\Facades\Event;
use App\Listeners\Orders\ProcessPayment;
use App\Events\Orders\OrderPaymentFailed;
use App\Exceptions\PaymentFailedException;
use App\Cart\Payments\Gateways\StripeGateway;
use App\Cart\Payments\Gateways\StripeGatewayCustomer;

class ProcessPaymentListenerTest extends TestCase
{
    protected function mockEvent()
    {
        $user = User::factory()->create();

        $payment = PaymentMethod::factory()->create([
            'user_id' => $user->id
        ]);

        $event = new OrderCreated(
            $order = Order::factory()->create([
                'user_id' => $user->id,
                'payment_method_id' => $payment->id
            ])
        );

        return [$user, $payment, $event, $order];
    }

    protected function mockStripeFlow()
    {
        $gateway = Mockery::mock(StripeGateway::class);

        $gateway->shouldReceive('withUser')
            ->andReturn($gateway)
            ->shouldReceive('getCustomer')
            ->andReturn(
                $customer = Mockery::mock(StripeGatewayCustomer::class)
            );

        return [$gateway, $customer];
    }

    public function test_it_charges_the_chosen_payment_the_correct_amount()
    {
        Event::fake();

        [$user, $payment, $event, $order] = $this->mockEvent();
        [$gateway, $customer] = $this->mockStripeFlow();

        $customer->shouldReceive('charge')
            ->with(
                $order->paymentMethod,
                $order->total()->amount()
            );

        $listener = new ProcessPayment($gateway);

        $listener->handle($event);
    }

    public function test_it_fires_the_order_paid_event()
    {
        Event::fake();

        [$user, $payment, $event, $order] = $this->mockEvent();
        [$gateway, $customer] = $this->mockStripeFlow();

        $customer->shouldReceive('charge')
            ->with(
                $order->paymentMethod,
                $order->total()->amount()
            );

        $listener = new ProcessPayment($gateway);

        $listener->handle($event);

        Event::assertDispatched(OrderPaid::class, fn ($event) => $event->order->id == $order->id);
    }

    public function test_it_fires_the_order_failed_event()
    {
        Event::fake();

        [$user, $payment, $event, $order] = $this->mockEvent();
        [$gateway, $customer] = $this->mockStripeFlow();

        $customer->shouldReceive('charge')
            ->with(
                $order->paymentMethod,
                $order->total()->amount()
            )
            ->andThrow(PaymentFailedException::class);

        $listener = new ProcessPayment($gateway);

        $listener->handle($event);

        Event::assertDispatched(OrderPaymentFailed::class, fn ($event) => $event->order->id == $order->id);
    }
}
