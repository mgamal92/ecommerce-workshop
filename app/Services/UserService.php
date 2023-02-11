<?php

namespace App\Services;

use App\Models\User;
use Spatie\Permission\Traits\HasRoles;

class UserService extends BaseServices
{
    use HasRoles;
    protected $model;
    
    public function create($request){
        $this->model = new User();
        $user = auth()->user();
        if($user->hasRole('admin')) {
            $data = [
                'name'  => $request->name,
                'email' => $request->email,
                'password'=> bcrypt($request->password),
            ];

            if($request->is_admin == 1){
                // $user->assignRole('admin');
                $new_user = $this->store($this->model,$data);
                $new_user->assignRole('admin');
                return $new_user;
            }else if($request->is_admin == 0){
                $new_user = $this->store($this->model,$data);
                $new_user->assignRole('user');
                return $new_user;
            }
        }else if($user->hasRole('user')){
            return response()->json(["error" => "error add user ... please check your role"]);
        }
    }
}
