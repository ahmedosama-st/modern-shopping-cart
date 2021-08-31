<?php

namespace App\Providers;

use App\Cart\Cart;
use Stripe\Stripe;
use App\Cart\Payments\Gateway;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use App\Cart\Payments\Gateways\StripeGateway;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Cart::class, function ($app) {
            if (!$app->auth->user()) {
                return null;
            }

            $app->auth->user()->load([
                'cart.stock'
            ]);

            return new Cart($app->auth->user());
        });

        $this->app->singleton(Gateway::class, fn () => new StripeGateway());
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Model::preventLazyLoading(!$this->app->isProduction());

        Stripe::setApiKey(config('services.stripe.secret'));
    }
}
