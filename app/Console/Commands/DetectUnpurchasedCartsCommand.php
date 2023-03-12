<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Events\UsersWithLeftItemsInCart;

class DetectUnpurchasedCartsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'detect:carts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Detect Unpurchased Carts';

    public function handle()
    {
        return event(new UsersWithLeftItemsInCart());
    }
}
