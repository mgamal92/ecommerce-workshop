<?php

namespace Tests\Feature\CustomerAuth;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CustomerAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    private Customer $customer;

    //setup to run before each test method
    protected function setUp(): void
    {
        parent::setUp();
        $this->customer = Customer::factory()->create();
    }

    public function test_customers_can_authenticate_successfully()
    {
        $response = $this->postJson(route('customers.login'), [
            'email' => $this->customer->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
    }

    public function test_customers_can_not_authenticate_with_invalid_password()
    {
        $response = $this->postJson(route('customers.login'), [
            'email' => $this->customer->email,
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401);
    }
}
