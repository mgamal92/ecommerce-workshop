<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;
class AdminController extends Controller
{
    use HasRoles;
    // admin can add new user with specfic permissions this user can have role admin or user
    public function create_new_user(RegisterRequest $request)
    {
        if(auth()->user()->hasRole('admin')) {
            // dd("welcome admin");
            if($request->is_admin == 1){
                $user = User::create([
                    'name'  => $request->name,
                    'email' => $request->email,
                    'password'=> bcrypt($request->password),
                ]);
                $user->assignRole('admin');
                return new UserResource($user);
            }else if($request->is_admin == 0){
                $user = User::create([
                    'name'  => $request->name,
                    'email' => $request->email,
                    'password'=> bcrypt($request->password),
                ]);

                $user->assignRole('user');

                return new UserResource($user);
            }

        }else if(auth()->user()->hasRole('user')){
            return response()->json(["error" => "error add user ... please check your role"]);
        }
    }
}
