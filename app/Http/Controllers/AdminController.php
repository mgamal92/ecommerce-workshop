<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Helper;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminController extends Controller
{
    // admin can add new user with specfic permissions this user can have role admin or user
     public function create_new_user(RegisterRequest $request)
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
