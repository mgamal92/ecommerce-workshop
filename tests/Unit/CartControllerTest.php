<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    /*
    public function test_add_product_to_cart()
    {
        $product = Product::create([
            'category_id' => 1,
            'name' => 'test',
            'price' => 50,
            'quantity' => 10,
        ]);

        $cart = (new CartService())->addToCart($product, random_int(1,10));
        $this->assertTrue($cart);
    }
    */

}
