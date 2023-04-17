<?php

namespace App\Providers;

use App\Contracts\PaymentFlow;
use App\Models\Order;
use Exception;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PaymentFlow::class, function ($app, $args) {

            $paymentMethod = request()->payment_method ?? Order::CARD_PAYMENT;
            $allowedMethod = config('paymob.payments');

            if (!array_key_exists($paymentMethod, $allowedMethod)) {
                throw new Exception("Invalid Payment Method");
            }

            $paymentClass = $allowedMethod[$paymentMethod];

            return app()->make($paymentClass);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
