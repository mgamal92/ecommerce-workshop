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

    /* 
        /- test retrieve all data
    */
    public function test_the_controller_returns_all_categories_successfully()
    {
        $user = User::create([
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => bcrypt('password')
        ]);
        $categories = Category::create(['id' => 1]);
        $response = $this->actingAs($user)->getJson('api/categories');
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'attributes' => [
                    'created_at' => $categories->created_at,
                    'updated_at' => $categories->updated_at
                ]
            ]);
    }

    /* 
        /- test create new category
    */
    public function test_the_controller_create_new_category()
    {
        $user = User::create([
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => bcrypt('password')
        ]);
        $category = Category::create(['id' => 1]);
        $response = $this->actingAs($user)->postJson('api/categories');
        $response->assertStatus(201);
        $this->assertModelExists($category);
    }

    /* 
        /- test update category
    */
    public function test_the_controller_update_category()
    {
        $user = User::create([
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => bcrypt('password')
        ]);
        $category = Category::create(['id' => 1]);
        $response = $this->actingAs($user)->putJson("api/categories/1", []);
        $response->assertStatus(200);
        $this->assertModelExists($category);
    }

    public function test_the_controller_show_category()
    {
        $user = User::create([
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => bcrypt('password')
        ]);
        $category = Category::create(['id' => 1]);
        $response = $this->actingAs($user)->getJson("api/categories/1");
        $response->assertStatus(200);
        $this->assertModelExists($category);
    }

    public function test_the_controller_delete_category()
    {
        $user = User::create([
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => bcrypt('password')
        ]);
        $category = Category::create(['id' => 1]);
        $response = $this->actingAs($user)->deleteJson("api/categories/{$category->id}");
        $response->assertStatus(204);
        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }
}
