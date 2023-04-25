<?php

namespace App\Http\Controllers\CustomerAuth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomerController;
use App\Http\Requests\CustomerAuth\RegisterRequest;
use App\Http\Resources\CustomersResource;
use App\Models\Customer;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisteredUserController extends Controller
{
    use HttpResponses;

    public function __construct(protected CustomerController $controller)
    {
    }
    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterRequest $request)
    {
        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        //save address and avatar
        $this->controller->store($request, $customer);

        return (new CustomersResource($customer))->additional([
            'token' => $customer->createToken('customerToken')->plainTextToken,
        ]);
    }
}
