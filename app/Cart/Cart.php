<?php

namespace App\Cart;

use App\Models\User;

class Cart
{
    public function __construct(protected User $user)
    {
        $this->user = $user;
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
