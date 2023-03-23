<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     * //* disabled CSRF for /register URI, this is in order to solve (error: CSRF token mismatch), this error caused by breeze using default web middleware for auth.php registration in api stack instead of "api" middleware
     */
    protected $except = [
        '/api/register',
        '/api/login',
        '/api/logout',
        '/api/customers/register',
        '/api/customers/login',
        '/api/customers/logout',
    ];
}
