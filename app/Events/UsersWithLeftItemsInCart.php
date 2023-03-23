<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Cart;

class UsersWithLeftItemsInCart
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var string $carts */
    public $carts;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        $carts = Cart::with('customer')
                    ->whereDate( 'created_at', '<=', \Carbon\Carbon::yesterday()->toDateTimeString())
                    ->where('offer_sent', '0')
                    ->get();
        
        $this->carts = $carts;
    }

}
