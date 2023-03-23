<?php

namespace App\Listeners;

use App\Events\UsersWithLeftItemsInCart;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Jobs\DetectUnpurchasedCarts;

class SendOfferEmail implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\UsersWithLeftItemsInCart  $event
     * @return void
     */
    public function handle(UsersWithLeftItemsInCart $event)
    {
        foreach($event->carts as $cart) {
            $email = $cart->customer->email;
            dispatch(new DetectUnpurchasedCarts($email));
        }

        // Artisan::call('queue:work');
    }
}
