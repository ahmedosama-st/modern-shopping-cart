<?php

namespace Tests\Feature\Orders;

use Tests\TestCase;
use App\Models\User;
use App\Models\Stock;
use App\Models\Address;
use App\Models\PaymentMethod;
use App\Models\ShippingMethod;
use App\Models\ProductVariation;
use App\Events\Orders\OrderCreated;
use Illuminate\Support\Facades\Event;

class OrderStoreTest extends TestCase
{
    protected function orderDependencies(User $user)
    {
        $stripeCustomer = \Stripe\Customer::create([
            'email' => $user->email
        ]);

        $user->update([
            'gateway_customer_id' => $stripeCustomer->id
        ]);

        $address = Address::factory()->create([
            'user_id' =>$user->id
        ]);

        $shipping = ShippingMethod::factory()->create();

        $shipping->countries()->attach($address->country);

        $payment = PaymentMethod::factory()->create([
            'user_id' => $user->id
        ]);

        return [$address, $shipping, $payment];
    }

    protected function productWithStock()
    {
        $product = ProductVariation::factory()->create();

        Stock::factory()->create([
            'product_variation_id' => $product->id
        ]);

        return $product;
    }

    public function test_it_fails_if_unauthenticated()
    {
        $this->json('POST', 'api/orders')
            ->assertStatus(401);
    }

    public function test_it_requires_an_address()
    {
        $user = User::factory()->create();

        $user->cart()->sync(
            $product = $this->productWithStock()
        );

        $this->jsonAs($user, 'POST', 'api/orders')
            ->assertJsonValidationErrors(['address_id']);
    }

    public function test_it_requires_an_address_that_exists()
    {
        $user = User::factory()->create();

        $user->cart()->sync(
            $product = $this->productWithStock()
        );

        $this->jsonAs($user, 'POST', 'api/orders', [
            'address_id' => 1
        ])
            ->assertJsonValidationErrors(['address_id']);
    }

    public function test_it_requires_an_address_that_belongs_to_the_authenticated_user()
    {
        $user = User::factory()->create();

        $user->cart()->sync(
            $product = $this->productWithStock()
        );
        $address = Address::factory()->create([
            'user_id' => User::factory()->create()->id
        ]);

        $this->jsonAs($user, 'POST', 'api/orders', [
            'address_id' => $address->id
        ])
            ->assertJsonValidationErrors(['address_id']);
    }

    public function test_it_requires_a_shipping_method()
    {
        $user = User::factory()->create();

        $user->cart()->sync(
            $product = $this->productWithStock()
        );

        $this->jsonAs($user, 'POST', 'api/orders')
            ->assertJsonValidationErrors(['shipping_method_id']);
    }

    public function test_it_requires_a_shipping_method_that_exists()
    {
        $user = User::factory()->create();

        $user->cart()->sync(
            $product = $this->productWithStock()
        );

        $this->jsonAs($user, 'POST', 'api/orders', [
            'shipping_method_id' => 1
        ])
            ->assertJsonValidationErrors(['shipping_method_id']);
    }

    public function test_it_attaches_the_products_to_the_order()
    {
        $user = User::factory()->create();

        $user->cart()->sync(
            $product = $this->productWithStock()
        );

        [$address, $shipping, $payment] = $this->orderDependencies($user);

        $response = $this->jsonAs($user, 'POST', 'api/orders', [
            'address_id' => $address->id,
            'shipping_method_id' => $shipping->id,
            'payment_method_id' => $payment->id
        ]);

        $this->assertDatabaseHas('product_variation_order', [
            'product_variation_id' => $product->id,
            'order_id' => json_decode($response->getContent())->data->id
        ]);
    }

    public function test_it_fires_an_order_created_event()
    {
        Event::fake();

        $user = User::factory()->create();

        $user->cart()->sync(
            $product = $this->productWithStock()
        );

        [$address, $shipping, $payment] = $this->orderDependencies($user);

        $response = $this->jsonAs($user, 'POST', 'api/orders', [
            'address_id' => $address->id,
            'shipping_method_id' => $shipping->id,
            'payment_method_id' => $payment->id
        ]);

        Event::assertDispatched(OrderCreated::class, fn ($event) => $event->order->id === json_decode($response->getContent())->data->id);
    }

    public function test_it_empties_the_cart_after_ordering()
    {
        $user = User::factory()->create();

        $user->cart()->sync(
            $product = $this->productWithStock()
        );

        [$address, $shipping, $payment] = $this->orderDependencies($user);

        $response = $this->jsonAs($user, 'POST', 'api/orders', [
            'address_id' => $address->id,
            'shipping_method_id' => $shipping->id,
            'payment_method_id' => $payment->id
        ]);

        $this->assertEmpty($user->cart);
    }

    public function test_it_requires_a_valid_shipping_method_that_exists_within_the_country_of_the_given_address()
    {
        $user = User::factory()->create();

        $address = Address::factory()->create([
            'user_id' => $user->id,
        ]);

        $user->cart()->sync(
            $product = $this->productWithStock()
        );

        $shipping = ShippingMethod::factory()->create();

        $this->jsonAs($user, 'POST', 'api/orders', [
            'shipping_method_id' => $shipping->id,
            'address_id' => $address->id
        ])->assertJsonValidationErrors(['shipping_method_id']);
    }

    public function test_it_can_create_an_order()
    {
        $user = User::factory()->create();

        $user->cart()->sync([
            ($this->productWithStock())->id => [
                'quantity' => 1
            ]
        ]);

        [$address, $shipping, $payment] = $this->orderDependencies($user);

        $this->jsonAs($user, 'POST', 'api/orders', [
            'address_id' => $address->id,
            'shipping_method_id' => $shipping->id,
            'payment_method_id' => $payment->id
        ])->assertStatus(201);

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'address_id' => $address->id,
            'shipping_method_id' => $shipping->id,
            'payment_method_id' => $payment->id,

        ]);
    }

    public function test_it_fails_to_create_order_if_cart_is_empty()
    {
        $user = User::factory()->create();

        $user->cart()->sync([
            ($this->productWithStock())->id => [
                'quantity' => 0
            ]
        ]);

        [$address, $shipping, $payment] = $this->orderDependencies($user);

        $this->jsonAs($user, 'POST', 'api/orders', [
            'address_id' => $address->id,
            'shipping_method_id' => $shipping->id,
            'payment_method_id' => $payment->id
        ])->assertStatus(400);
    }
}
