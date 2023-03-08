<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    private Customer $customer;
    private Category $category;
    private Product $product;

    //setup to run before each test method
    protected function setUp(): void
    {
        parent::setUp();

        $this->customer = Customer::factory()->create();
        $this->category = Category::factory()->create();
        $this->product = Product::factory()->create();
    }

    /**
     * A basic test example.
     */
    public function test_the_controller_returns_customer_existing_cart()
    {
        $cart = Cart::factory()->for($this->customer)->create();
        $response = $this->actingAs($this->customer)->getJson('api/carts');
        $response->assertStatus(200);
        $this->assertModelExists($cart);
    }

    public function test_the_controller_returns_customer_cart_not_exist()
    {
        $response = $this->actingAs($this->customer)->getJson('api/carts');
        $response->assertStatus(404);
    }

    public function test_the_controller_add_existing_product_with_enough_quantity_to_cart()
    {
        $response = $this->actingAs($this->customer)->postJson("api/carts/add-to-cart/{$this->product->id}", [
            'quantity' => 1,
        ]);

        $response->assertStatus(201);
        $this->assertModelExists($this->product);
    }

    public function test_the_controller_add_existing_product_without_enough_quantity_to_cart()
    {
        $response = $this->actingAs($this->customer)->postJson("api/carts/add-to-cart/{$this->product->id}", [
            'quantity' => random_int(1000000,9999999),
        ]);

        $response->assertStatus(400);
        $this->assertModelExists($this->product);
    }

    public function test_the_controller_add_non_existing_product_to_cart()
    {
        $response = $this->actingAs($this->customer)->postJson("api/carts/add-to-cart/0", [
            'quantity' => random_int(1,10),
        ]);

        $response->assertStatus(404);
    }
}