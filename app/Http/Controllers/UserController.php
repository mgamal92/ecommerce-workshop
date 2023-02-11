<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    // admin can add new user with specfic permissions this user can have role admin or user
    public function create_user(CreateUserRequest $request)
    {
        return new UserResource($this->userService->create($request));
    }
}
