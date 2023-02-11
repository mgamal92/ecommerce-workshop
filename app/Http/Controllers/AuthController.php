<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Http\Helpers\Helper;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AuthController extends Controller
{

    public function login(LoginRequest $request)
    {
        if(!Auth::attempt($request->only('email', 'password'))) {
            Helper::sendError('Email Or Password is incorrect');
        }

        return new UserResource(auth()->user());
    }

    public function register(RegisterRequest $request)
    {
        if($request->is_admin == 1){
            // return "admin";
            $user = User::create([
                'name'  => $request->name,
                'email' => $request->email,
                'password'=> bcrypt($request->password),
            ]);
            $user->assignRole('admin');
            return new UserResource($user);
        }else if($request->is_admin == 0){
            // return "user";
            $user = User::create([
                'name'  => $request->name,
                'email' => $request->email,
                'password'=> bcrypt($request->password),
            ]);
            $user->assignRole('user');
            return new UserResource($user);
        }else{
            return "none";
        }
    }
}

