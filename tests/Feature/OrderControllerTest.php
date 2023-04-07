<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Order $order;

    //setup to run before each test method
    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'admin']);
        $this->user = User::factory()->create();
        $this->user->assignRole('admin');
        $this->order = Order::factory()->create();
    }

    public function test_the_controller_returns_all_orders_successfully()
    {
        $orders = $this->order;
        $response = $this->actingAs($this->user,)->getJson('api/orders');
        $response->assertStatus(200);
        $this->assertModelExists($orders);
    }

    public function test_the_controller_show_order()
    {
        $order = $this->order;
        $response = $this->actingAs($this->user)->getJson("api/orders/{$order->id}");
        $response->assertStatus(200);
        $this->assertModelExists($order);
    }
}
