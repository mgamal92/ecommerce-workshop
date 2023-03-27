<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class StaffControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    //setup to run before each test method
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_the_controller_returns_all_staff_members_successfully()
    {
        $staff = $this->user;
        $response = $this->actingAs($this->user)->getJson(route('user.index'));
        $response->assertStatus(200);
        $this->assertModelExists($staff);
    }

    public function test_the_controller_update_user()
    {
        $user = $this->user;
        $response = $this->actingAs($this->user)->putJson(route('user.show', $user->id), [
            'name' => "testing"
        ]);
        $response->assertStatus(200);
        $this->assertModelExists($user);
    }

    public function test_the_controller_show_user()
    {
        $user = $this->user;
        $response = $this->actingAs($this->user)->getJson(route('user.show', $user->id));
        $response->assertStatus(200);
        $this->assertModelExists($user);
    }

    public function test_the_controller_delete_user()
    {
        $user = $this->user;
        $role = Role::create(['name' => 'admin']);
        $this->user->assignRole($role->name);
        $response = $this->actingAs($this->user)->deleteJson(route('user.destroy', $user->id));
        $response->assertStatus(200);
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }
}
