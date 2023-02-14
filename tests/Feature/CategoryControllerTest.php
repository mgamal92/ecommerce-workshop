<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

        $this->user = User::factory()->create();
        $this->category = Category::factory()->create();
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
        $response = $this->actingAs($this->user)->postJson('api/categories');
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
}
