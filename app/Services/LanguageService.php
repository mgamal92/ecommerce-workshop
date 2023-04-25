<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class LanguageService extends BaseServices
{
    public function retrieve($model)
    {
        $langs = Cache::remember('lang', Carbon::now()->endOfWeek(Carbon::FRIDAY), function () {
            return DB::table('languages')->get();
        });
        return $langs;
    }
}
