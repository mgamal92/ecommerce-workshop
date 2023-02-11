<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest as AuthLoginRequest;
use App\Http\Resources\UserResource;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class AuthController extends Controller
{
    protected $registeredUserController;
    protected $authenticatedSessionController;

    public function __construct(RegisteredUserController $registeredUserController, AuthenticatedSessionController $authenticated){
        $this->registeredUserController = $registeredUserController;
        $this->authenticatedSessionController = $authenticated;
    }

    public function login(AuthLoginRequest $request)
    {
        return new UserResource($this->authenticatedSessionController->store($request));
    }
    
    public function register(Request $request)
    {
        return new UserResource($this->registeredUserController->store($request));
    }
}
