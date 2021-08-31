<?php

namespace App\Http\Middleware\Cart;

use Closure;
use App\Cart\Cart;
use Illuminate\Http\Request;

class Sync
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
        $this->cart->sync();

        if ($this->cart->hasChanged()) {
            return response()->json([
                'message' => 'Some items in your cart have changed, please review before placing your order'
            ], 409);
        }

        return $next($request);
    }
}
