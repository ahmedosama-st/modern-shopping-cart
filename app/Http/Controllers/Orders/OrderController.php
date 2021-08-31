<?php

namespace App\Http\Controllers\Orders;

use App\Cart\Cart;
use Illuminate\Http\Request;
use App\Events\Orders\OrderCreated;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Http\Requests\Orders\OrderStoreRequest;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api']);
        $this->middleware(['cart.sync', 'cart.isnotempty'])->only('store');
    }

    public function index(Request $request)
    {
        $orders = $request->user()->orders()
            ->with([
                'products',
                'address',
                'shippingMethod',
                'products.type',
                'products.stock',
                'products.product',
                'products.product.variations',
                'products.product.variations.stock'
            ])
            ->latest()
            ->paginate(10);

        return OrderResource::collection($orders);
    }

    public function store(OrderStoreRequest $request, Cart $cart)
    {
        $order = $this->createOrder($request, $cart);

        $order->products()->sync($cart->products()->forSyncing());

        event(new OrderCreated($order));

        return new OrderResource($order);
    }

    protected function createOrder(Request $request, Cart $cart)
    {
        return $request->user()->orders()->create(
            array_merge(
                $request->only(
                    ['address_id', 'shipping_method_id', 'payment_method_id']
                ),
                [
                    'subtotal' => $cart->subtotal()->amount()
                ]
            )
        );
    }
}
