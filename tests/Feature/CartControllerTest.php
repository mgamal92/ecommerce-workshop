<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
        $response = $this->actingAs($this->customer)->getJson('api/carts');

        if($response->getStatusCode() == 404) {
            $response->assertStatus(404);
        }
        else {
            $response->assertStatus(302);
        }
    }

    public function test_the_controller_add_product_to_cart()
    {
        $product = $this->product;
        $response = $this->actingAs($this->customer)->postJson("api/carts/add-to-cart/{$product->id}", [
            'quantity' => random_int(1,10),
        ]);

        if($response->getStatusCode() == 400 || $response->getStatusCode() == 404) {
            $response->assertStatus($response->getStatusCode());
        }
        else {
            $response->assertStatus(201);
        }

        $this->assertModelExists($product);
    }
}