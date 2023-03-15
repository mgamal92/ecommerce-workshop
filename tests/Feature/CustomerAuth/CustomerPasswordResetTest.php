<?php

namespace Tests\Feature\CustomerAuth;

use App\Models\Customer;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class CustomerPasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_link_can_be_requested()
    {
        Notification::fake();

        $customer = Customer::factory()->create();

        $this->post('api/customers/forgot-password', ['email' => $customer->email]);

        Notification::assertSentTo($customer, ResetPassword::class);
    }

    public function test_password_can_be_reset_with_valid_token()
    {
        Notification::fake();

        $customer = Customer::factory()->create();

        $this->post('api/customers/forgot-password', ['email' => $customer->email]);

        Notification::assertSentTo($customer, ResetPassword::class, function ($notification) use ($customer) {
            $response = $this->post('api/customers/reset-password', [
                'token' => $notification->token,
                'email' => $customer->email,
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);

            $response->assertSessionHasNoErrors();

            return true;
        });
    }
}
