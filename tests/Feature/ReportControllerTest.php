<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportControllerTest extends TestCase
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

    /* 
        /- Test report for the customers who registered within a specific period
    */
    public function test_report_for_customers_who_registered_within_specific_period()
    {
        $date = $this->customer->first()->created_at->format('d-m-Y');

        $response = $this->actingAs($this->user)->getJson(route('reports.periods', ['table' => 'customers', 'from' => $date, 'to' => $date]));

        $response->assertSuccessful();
    }

    /* 
        /- Test report for the members who registered within a specific period
    */
    public function test_report_for_members_who_registered_within_specific_period()
    {
        $date = $this->user->first()->created_at->format('d-m-Y');

        $response = $this->actingAs($this->user)->getJson(route('reports.periods', ['table' => 'users', 'from' => $date, 'to' => $date]));

        $response->assertSuccessful();
    }
}
