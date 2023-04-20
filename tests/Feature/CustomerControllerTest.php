<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CustomerControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Customer $customer;

    //setup to run before each test method
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->customer = Customer::factory()->create();
    }

    public function test_the_controller_returns_all_customers_successfully()
    {
        $customers = $this->customer;
        $role = Role::create(['name' => 'admin']);
        $this->user->assignRole($role->name);
        $response = $this->actingAs($this->user)->getJson(route('customers.index'));
        $response->assertStatus(200);
        $this->assertModelExists($customers);
    }

    public function test_the_controller_update_customer()
    {
        $customer = $this->customer;
        $response = $this->actingAs($this->user)->putJson(route('customer.update', ['customer' => $customer->id, 'address' => 1]), [
            'name' => 'test',
            'address' => 'testing address',
            'email' => 'a@a.com',
            'address' => '',
            'building_no' => '',
            'country' => '',
            'city' => '',
            'country_code' => ''
        ]);
        $response->assertStatus(200);
        $this->assertModelExists($customer);
    }

    public function test_the_controller_show_customer()
    {
        $customer = $this->customer;
        $response = $this->actingAs($this->user)->getJson("api/customers/{$customer->id}");
        $response->assertStatus(200);
        $this->assertModelExists($customer);
    }

    public function test_the_controller_delete_customer()
    {
        $customer = $this->customer;
        $role = Role::create(['name' => 'admin']);
        $this->user->assignRole($role->name);
        $response = $this->actingAs($this->user)->deleteJson("api/customers/{$customer->id}");
        $response->assertStatus(200);
        $this->assertDatabaseMissing('customers', [
            'id' => $customer->id,
        ]);
    }

    public function test_the_controller_add_new_address()
    {
        $customer = $this->customer;
        $response = $this->actingAs($customer, 'api-customer')->postJson(route('customer.new.address'), [
            'address' => 'testing',
            'building_no' => '20',
            'country' => 'TEST',
            'country_code' => 'test',
            'city' => 'test'
        ]);

        $response->assertStatus(200);
        $this->assertModelExists($customer);
    }
}
