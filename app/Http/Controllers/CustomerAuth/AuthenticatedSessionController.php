<?php

namespace App\Http\Controllers\CustomerAuth;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerAuth\LoginRequest;
use App\Http\Resources\CustomersResource;
use App\Models\Customer;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    use HttpResponses;
    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LoginRequest $request)
    {
        if (!Auth::guard('customer')->attempt($request->only('email', 'password'))) {
            return $this->error(null, 'Credentials does not match', 401);
        }

        $customer = Customer::where('email', $request->email)->first();

        return (new CustomersResource($customer))->additional([
            'token' => $customer->createToken('customerToken')->plainTextToken
        ]);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        $customer = Auth::guard('customer')->user()->tokens()->delete();

        if ($customer) {
            return $this->success(null, 'you have logged out successfully');
        }
    }
}
