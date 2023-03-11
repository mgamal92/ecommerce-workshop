<?php

namespace Tests\Feature\CustomerAuth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_customers_can_register()
    {
        $response = $this->postJson(route('customers.register'), [
            'name' => 'Test Customer',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201);
    }

    public function test_customer_register_validation_error()
    {
        $response = $this->post(route('customers.register'), [
            'name' => 'Test Customer',
            'email' => 'test'
        ]);

        $response->assertStatus(422);
    }
}
