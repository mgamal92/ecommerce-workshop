<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    //setup to run before each test method
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }


    public function test_the_controller_returns_a_successful_payment_response()
    {
        $json =  [
            "delivery_needed" => "false",
            "amount_cents" => "1000",
            "currency" => "EGP",
            "shipping_fees" => 22,
            "total_amount" => 30,
            "payment_method" => 'card',
            "items" => [],
            "shipping_data" => [
                "email" => "claudette09@exa.com",
                "phone_number" => "01010101010",
                "first_name" => "Clifford",
                "last_name" => "Nicolas",
                "apartment" => "803",
                "floor" => "42",
                "street" => "Ethan Land",
                "building" => "8028",
                "city" => "Jaskolskiburgh",
                "country" => "CR",
                "state" => "Utah"
            ]
        ];
        $response = $this->actingAs($this->user)->postJson('api/payment/paymob/processing', $json);

        $response->assertStatus(200);
    }
}
