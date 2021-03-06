<?php

namespace App\Cart;

use App\Models\User;
use App\Models\ShippingMethod;

class Cart
{
    protected bool $changed = false;

    protected ?ShippingMethod $shipping = null;

    public function __construct(protected User $user)
    {
        $this->user = $user;
    }

    public function products()
    {
        return $this->user->cart;
    }

    public function update($productId, $quantity)
    {
        $this->user->cart()->updateExistingPivot($productId, [
            'quantity' => $quantity
        ]);
    }

    public function delete($productId)
    {
        $this->user->cart()->detach($productId);
    }

    public function empty()
    {
        $this->user->cart()->detach();
    }

    public function isEmpty()
    {
        return $this->user->cart->sum('pivot.quantity') <= 0;
    }

    public function subtotal()
    {
        $subtotal = $this->user->cart->sum(fn ($product) => $product->price->amount() * $product->pivot->quantity);

        return new Money($subtotal);
    }

    public function sync()
    {
        $this->user->load(['cart.stock']);

        $this->user->cart->each(function ($product) {
            $quantity = $product->minStock($product->pivot->quantity);

            if ($quantity != $product->pivot->quantity) {
                $this->changed = $quantity != $product->pivot->quantity;
            }

            $product->pivot->update([
                'quantity' => $quantity
            ]);
        });
    }

    public function hasChanged()
    {
        return $this->changed;
    }

    public function withShipping($shippingId)
    {
        $this->shipping = ShippingMethod::find($shippingId);

        return $this;
    }

    public function total()
    {
        if ($this->shipping) {
            return $this->subtotal()->add($this->shipping?->price);
        }

        return $this->subtotal();
    }

    public function add($products)
    {
        $this->user->cart()->syncWithoutDetaching(
            $this->getStorePayloads($products)
        );
    }

    public function getStorePayloads($products)
    {
        return collect($products)->keyBy('id')->map(
            fn ($product) => [
                'quantity' => $product['quantity'] + $this->getCurrentQuantity($product['id'])
            ]
        )->toArray();
    }

    public function getCurrentQuantity($productId)
    {
        if ($product = $this->user->cart->where('id', $productId)->first()) {
            return $product->pivot->quantity;
        }

        return 0;
    }
}
