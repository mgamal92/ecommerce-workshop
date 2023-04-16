<?php

namespace App\Http\Controllers;

use App\Http\Resources\CountriesResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;


class CountryController extends Controller
{
    //return all countries
    public function index()
    {
        Cache::remember('countries', Carbon::now()->endOfWeek(Carbon::FRIDAY), function () {
            return DB::table('countries')->get();
        });

        return CountriesResource::collection(Cache::get('countries'));
    }
}
