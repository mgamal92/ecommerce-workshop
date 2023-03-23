<?php

namespace Tests\Feature\CustomerAuth;

use App\Models\Customer;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class CustomerEmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_can_be_verified()
    {
        $customer = Customer::factory()->create([
            'email_verified_at' => null,
        ]);

        Event::fake();

        $verificationUrl = URL::temporarySignedRoute(
            'customers.verification.verify',
            now()->addMinutes(60),
            ['id' => $customer->id, 'hash' => sha1($customer->email)]
        );

        $response = $this->actingAs($customer, 'api-customer')->get($verificationUrl);

        Event::assertDispatched(Verified::class);
        $this->assertTrue($customer->fresh()->hasVerifiedEmail());
        $response->assertRedirect(config('app.frontend_url').RouteServiceProvider::HOME.'?verified=1');
    }

    public function test_email_is_not_verified_with_invalid_hash()
    {
        $customer = Customer::factory()->create([
            'email_verified_at' => null,
        ]);

        $verificationUrl = URL::temporarySignedRoute(
            'customers.verification.verify',
            now()->addMinutes(60),
            ['id' => $customer->id, 'hash' => sha1('wrong-email')]
        );

        $this->actingAs($customer, 'api-customer')->get($verificationUrl);

        $this->assertFalse($customer->fresh()->hasVerifiedEmail());
    }
}
