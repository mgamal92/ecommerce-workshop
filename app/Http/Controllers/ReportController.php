<?php

namespace App\Http\Controllers;

use App\Http\Resources\CustomersResource;
use App\Http\Resources\UsersResource;
use App\Models\Customer;
use App\Models\User;
use App\Traits\HttpResponses;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ReportController extends Controller
{
    use HttpResponses;

    /**
     * Report for any model passed who registered within a specific period.
     *
     * @return \Illuminate\Http\Response
     */

    public function specificPeriodReport($table, $from, $to)
    {
        $models = config('reports.specificPeriodReports');

        if (!array_key_exists($table, $models)) {
            return new Exception('invalid table name');
        }

        $report = $models[$table];

        return app()->call($report, ['from' => $from, 'to' => $to]);
    }

    public function customersWithinPeriod($from, $to)
    {
        $startDate = Carbon::createFromFormat('d-m-Y', $from)->startOfDay();
        $endDate = Carbon::createFromFormat('d-m-Y', $to)->endOfDay();

        $customers = DB::table('customers')->whereBetween('created_at', [$startDate, $endDate])->paginate();

        $data = (CustomersResource::collection($customers))->additional(['customers_count' => Customer::count()]);

        return count($data) > 0 ? $data : $this->error(null, "no record found", 404);
    }
    public function membersWithinPeriod($from, $to)
    {
        $startDate = Carbon::createFromFormat('d-m-Y', $from)->startOfDay();
        $endDate = Carbon::createFromFormat('d-m-Y', $to)->endOfDay();

        $users = DB::table('users')->whereBetween('created_at', [$startDate, $endDate])->paginate();

        $data = (UsersResource::collection($users))->additional(['users_count' => User::count()]);

        return count($data) > 0 ? $data : $this->error(null, "no record found", 404);
    }
}
