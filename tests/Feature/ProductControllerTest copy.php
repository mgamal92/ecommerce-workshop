<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Product $product;
    private Category $category;

    //setup to run before each test method
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->category = Category::factory()->create();
        $this->product = Product::factory()->create();
    }

    public function test_the_controller_returns_all_products_successfully()
    {
        $products = $this->product;
        $response = $this->actingAs($this->user)->getJson('api/products');
        $response->assertStatus(200);
        $this->assertModelExists($products);
    }

    public function test_the_controller_create_new_product()
    {
        $product = $this->product;
        $response = $this->actingAs($this->user)->postJson('api/products', [
            'category_id' => $this->product->category_id,
            'name' => $this->product->name,
            'quantity' => $this->product->quantity,
            'price' => $this->product->price,
        ]);
        $response->assertStatus(201);
        $this->assertModelExists($product);
    }

    public function test_the_controller_update_product()
    {
        $product = $this->product;
        $response = $this->actingAs($this->user)->putJson("api/products/{$product->id}", []);
        $response->assertStatus(200);
        $this->assertModelExists($product);
    }

    public function test_the_controller_show_product()
    {
        $product = $this->product;
        $response = $this->actingAs($this->user)->getJson("api/products/{$product->id}");
        $response->assertStatus(200);
        $this->assertModelExists($product);
    }

    public function test_the_controller_delete_product()
    {
        $product = $this->product;
        $role = Role::create(['name' => 'admin']);
        $this->user->assignRole($role->name);
        $response = $this->actingAs($this->user)->deleteJson("api/products/{$product->id}");
        $response->assertStatus(200);
        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }

    public function test_search_for_products_by_product_name()
    {
        $product = $this->product;
        $response = $this->actingAs($this->user)->getJson("api/products/search/" . $product->name);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => $product->name,
        ]);
    }

    public function test_search_for_products_by_product_price()
    {
        $product = $this->product;
        $response = $this->actingAs($this->user)->getJson("api/products/search/" . $product->price);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'price' => (string) $product->price,
        ]);
    }

    public function test_search_for_products_by_product_created_at()
    {
        $product = $this->product;
        $response = $this->actingAs($this->user)->getJson("api/products/search/" . $product->created_at);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'created_at' => $product->created_at,
        ]);
    }

    public function test_search_for_products_by_category_name()
    {
        $product = $this->product;
        $response = $this->actingAs($this->user)->getJson("api/products/search/" . $product->category->name);
        $response->assertStatus(200);
        //"name" was used because can not use category's name in json format as it was not specified in the ProductResource
        $response->assertJsonFragment([
            'name' => $product->name
        ]);
    }
}
