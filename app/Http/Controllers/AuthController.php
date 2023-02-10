<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;;
use App\Http\Controllers\Controller;
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
                'is_admin' => $request->is_admin
            ]);
            $user_role = Role::where(['name'=>'admin'])->first();
            if($user_role){
                $user->assignRole($user_role);
            }
            User::where('email', $request->email)->update(['is_admin' => 1]);
            return new UserResource($user);
        }else if($request->is_admin == 0){
            // return "user";
            $user = User::create([
                'name'  => $request->name,
                'email' => $request->email,
                'password'=> bcrypt($request->password),
                'is_admin' => $request->is_admin
            ]);
            $user_role = Role::where(['name'=>'user'])->first();
            if($user_role){
                $user->assignRole($user_role);
            }
            User::where('email', $request->email)->update(['is_admin' => 0]);

            return new UserResource($user);
        }else{
            return "none";
        }
    }

}
