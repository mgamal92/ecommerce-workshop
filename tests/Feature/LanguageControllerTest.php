<?php

namespace Tests\Feature;

use App\Models\Language;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class LanguageControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Language $lang;

    //setup to run before each test method
    protected function setUp(): void
    {
        parent::setUp();

        $this->lang = Language::factory()->create();
        $this->user = User::factory()->create();
        $role = Role::create(['name' => 'admin']);
        $this->user->assignRole($role->name);
    }

    public function test_the_controller_returns_all_languages()
    {
        $response = $this->actingAs($this->user)->getJson(route('languages.index'));
        $response->assertStatus(200);
    }

    public function test_the_controller_update_language()
    {
        $lang = $this->lang;
        $response = $this->actingAs($this->user)->putJson(route('languages.show', $lang->id), [
            'name' => "testing"
        ]);
        $response->assertStatus(200);
        $this->assertModelExists($lang);
    }

    public function test_the_controller_show_language()
    {
        $lang = $this->lang;
        $response = $this->actingAs($this->user)->getJson(route('languages.show', $lang->id));
        $response->assertStatus(200);
        $this->assertModelExists($lang);
    }

    public function test_the_controller_delete_language()
    {
        $lang = $this->lang;
        $response = $this->actingAs($this->user)->deleteJson(route('languages.destroy', $lang->id));
        $response->assertStatus(200);
        $this->assertDatabaseMissing('languages', [
            'id' => $lang->id,
        ]);
    }
}
