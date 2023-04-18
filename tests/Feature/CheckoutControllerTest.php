<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutControllerTest extends TestCase
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


    public function test_the_controller_returns_a_successful_summary()
    {
        $product = $this->product;
        $response = $this->actingAs($this->customer, 'api-customer')->postJson(route('add.cart', $product->id), [
            'quantity' => 1
        ]);
        $response->assertStatus(201);
    }
}
