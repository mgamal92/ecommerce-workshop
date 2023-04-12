<?php

namespace App\Http\Controllers;

use App\Http\Resources\CustomersResource;
use App\Http\Resources\UsersResource;
use App\Models\Customer;
use App\Models\User;
use App\Traits\HttpResponses;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ReportController extends Controller
{
    use HttpResponses;
    /**
     * Report for the customers who registered within a specific period.
     *
     * @return \Illuminate\Http\Response
     */
    public function customersWithinPeriod($from, $to)
    {
        $startDate = Carbon::createFromFormat('d-m-Y', $from)->startOfDay();
        $endDate = Carbon::createFromFormat('d-m-Y', $to)->endOfDay();

        $customers = DB::table('customers')->whereBetween('created_at', [$startDate, $endDate])->paginate();

        $data = (CustomersResource::collection($customers))->additional(['customers_count' => Customer::count()]);

        return count($data) > 0 ? $data : $this->error(null, "no record found", 404);
    }

    /**
     * Report for the members who registered within a specific period.
     *
     * @return \Illuminate\Http\Response
     */
    public function membersWithinPeriod($from, $to)
    {
        $startDate = Carbon::createFromFormat('d-m-Y', $from)->startOfDay();
        $endDate = Carbon::createFromFormat('d-m-Y', $to)->endOfDay();

        $users = DB::table('users')->whereBetween('created_at', [$startDate, $endDate])->paginate();

        $data = (UsersResource::collection($users))->additional(['users_count' => User::count()]);

        return count($data) > 0 ? $data : $this->error(null, "no record found", 404);
    }
}
