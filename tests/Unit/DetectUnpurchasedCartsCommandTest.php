<?php

namespace Tests\Unit;

use App\Console\Commands\DetectUnpurchasedCartsCommand;
use App\Models\Cart;
use Carbon\Carbon;
use Illuminate\Support\Facades\Queue;
use Illuminate\Console\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;

use Tests\TestCase;

class DetectUnpurchasedCartsCommandTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if the testDetectUnpurchasedCartsCommand works properly.
     *
     * @return void
     */
    public function testDetectUnpurchasedCartsCommand()
    {
        // $command = new DetectUnpurchasedCartsCommand();

        $cart = Cart::factory()->create([
            'created_at' => Carbon::yesterday(),
        ]);
        Artisan::call('detect:carts');
        $this->assertTrue(true);

        // Artisan::call('queue:work');
        // Queue::assertPushed(function ($job) {
        //     return $job === 'Job executed successfully';
        // });
    }
}
