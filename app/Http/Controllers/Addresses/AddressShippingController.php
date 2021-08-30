<?php

namespace App\Http\Controllers\Addresses;

use App\Models\Address;
use App\Http\Controllers\Controller;
use App\Http\Resources\ShippingMethodResource;

class AddressShippingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api']);
    }

    public function action(Address $address)
    {
        $this->authorize('show', $address);

        return ShippingMethodResource::collection(
            $address->country->shippingMethods
        );
    }
}
