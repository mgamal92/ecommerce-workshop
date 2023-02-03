<?php

namespace Tests\Feature;

use App\Http\Resources\CategoriesResource;
use App\Models\Category;
use App\Models\User;
use App\Services\CategoryService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    //setup to run before each test method
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->createUser();
    }

    private function createUser()
    {
        return User::factory()->create();
    }

    /* 
        /- test retrieve all data
    */
    public function test_the_controller_returns_all_categories_successfully()
    {
        $categories = Category::factory()->create();
        $response = $this->actingAs($this->user)->getJson('api/categories');
        $response->assertStatus(200);
        $this->assertModelExists($categories);
    }

    /* 
        /- test create new category
    */
    public function test_the_controller_create_new_category()
    {
        $category = Category::factory()->create();
        $response = $this->actingAs($this->user)->postJson('api/categories');
        $response->assertStatus(201);
        $this->assertModelExists($category);
    }

    /* 
        /- test update category
    */
    public function test_the_controller_update_category()
    {
        $category = Category::factory()->create();
        $response = $this->actingAs($this->user)->putJson("api/categories/{$category->id}", []);
        $response->assertStatus(200);
        $this->assertModelExists($category);
    }

    public function test_the_controller_show_category()
    {
        $category = Category::factory()->create();
        $response = $this->actingAs($this->user)->getJson("api/categories/{$category->id}");
        $response->assertStatus(200);
        $this->assertModelExists($category);
    }

    public function test_the_controller_delete_category()
    {
        $category = Category::factory()->create();
        $response = $this->actingAs($this->user)->deleteJson("api/categories/{$category->id}");
        $response->assertStatus(200);
        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }
}
