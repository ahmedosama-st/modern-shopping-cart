<?php

namespace App\Providers;

use App\Cart\Cart;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

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
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Model::preventLazyLoading(!$this->app->isProduction());
    }
}
