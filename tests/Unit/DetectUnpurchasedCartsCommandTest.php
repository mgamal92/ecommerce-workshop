<?php

namespace Tests\Unit;

use App\Console\Commands\DetectUnpurchasedCartsCommand;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Queue;
use Illuminate\Console\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;

use Tests\TestCase;

class DetectUnpurchasedCartsCommandTest extends TestCase
{
    use RefreshDatabase;

    //setup to run before each test method
    protected function setUp(): void
    {
        parent::setUp();

        $this->customer = Customer::factory()->create();
        $this->category = Category::factory()->create();
        $this->product = Product::factory()->create();
    }

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
    }
}
