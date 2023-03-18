<?php

namespace App\Services;

use App\Http\Resources\UsersResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
class StaffService extends BaseServices
{
    protected $model;

    public function __construct()
    {
        $this->model = new User();
    }
    public function store($model, array $data){

        parent::store($this->model, $data);
        $user = User::where('email',$data['email'])->first();

        return (new UsersResource($user))->additional([
            'data' => [
                'token' => $user->createToken("API Token of " . $user->email)->plainTextToken
            ]
        ]);
    }

}
