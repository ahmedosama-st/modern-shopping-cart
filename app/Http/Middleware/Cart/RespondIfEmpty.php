<?php

namespace App\Http\Middleware\Cart;

use Closure;
use App\Cart\Cart;
use Illuminate\Http\Request;

class RespondIfEmpty
{
    public function __construct(protected ?Cart $cart)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($this->cart->isEmpty()) {
            return response()->json([
                'message' => 'Your cart is empty'
            ], 400);
        }

        return $next($request);
    }
}
