<?php

namespace App\Http\Controllers\PaymentMethods;

use Illuminate\Http\Request;
use App\Cart\Payments\Gateway;
use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentMethodResource;
use App\Http\Requests\Payments\PaymentMethodStoreRequest;

class PaymentMethodController extends Controller
{
    public function __construct(protected Gateway $gateway)
    {
        $this->middleware(['auth:api']);
    }

    public function index(Request $request)
    {
        return PaymentMethodResource::collection(
            $request->user()->paymentMethods
        );
    }

    public function store(PaymentMethodStoreRequest $request)
    {
        $card = $this->gateway->withUser($request->user())
            ->createCustomer()
            ->addCard($request->token);

        return new PaymentMethodResource($card);
    }
}
