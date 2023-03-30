<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Category $category;

    //setup to run before each test method
    protected function setUp(): void
    {
        parent::setUp();

        $this->setupPermissions();

        $this->user = User::factory()->create();
        $this->user->assignRole('admin');

        $this->category = Category::factory()->create();
    }

    protected function setupPermissions()
    {
        Permission::findOrCreate('list-categories');
        Permission::findOrCreate('create-categories');
        Permission::findOrCreate('update-categories');
        Permission::findOrCreate('delete-categories');

        Role::findOrCreate('admin')
            ->givePermissionTo(['list-categories', 'create-categories', 'update-categories', 'delete-categories']);

        $this->app->make(PermissionRegistrar::class)->registerPermissions();
    }

    /* 
        /- test retrieve all data
    */
    public function test_the_controller_returns_all_categories_successfully()
    {
        $categories = $this->category;
        $response = $this->actingAs($this->user)->getJson('api/categories');
        $response->assertStatus(200);
        $this->assertModelExists($categories);
    }

    /* 
        /- test create new category
    */
    public function test_the_controller_create_new_category()
    {
        $category = $this->category;
        $response = $this->actingAs($this->user)->postJson('api/categories', [
            'name' => $this->category->name,
            'products_count' => $this->category->products_count,
        ]);
        $response->assertStatus(201);
        $this->assertModelExists($category);
    }

    /* 
        /- test update category
    */
    public function test_the_controller_update_category()
    {
        $category = $this->category;
        $response = $this->actingAs($this->user)->putJson("api/categories/{$category->id}", []);
        $response->assertStatus(200);
        $this->assertModelExists($category);
    }

    public function test_the_controller_show_category()
    {
        $category = $this->category;
        $response = $this->actingAs($this->user)->getJson("api/categories/{$category->id}");
        $response->assertStatus(200);
        $this->assertModelExists($category);
    }

    public function test_the_controller_show_category_with_products()
    {
        $category = $this->category;
        $response = $this->actingAs($this->user)->getJson("api/categories/{$category->id}/products");
        $response->assertStatus(200);
        $this->assertModelExists($category);
    }

    public function test_the_controller_delete_category()
    {
        $category = $this->category;
        $response = $this->actingAs($this->user)->deleteJson("api/categories/{$category->id}");
        $response->assertStatus(200);
        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }

    public function test_increments_products_count_when_new_product_is_created()
    {
        $category = Category::factory()->create(['products_count' => 0]);
        $product = Product::factory()->create(['category_id' => $category->id]);

        $category->refresh();

        $this->assertEquals(1, $category->products_count);
    }
}
