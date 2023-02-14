<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UsersResource;
use App\Models\User;
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
        $request->validated();

        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->error(null, 'Credentials does not match', 401);
        }

        $user = User::whereEmail($request->email)->first();

        return (new UsersResource($user))->additional([
            'data' => [
                'token' => $user->createToken('API token of ' . $user->email)->plainTextToken
            ]
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
        $user = Auth::user()->tokens()->delete();

        if ($user) {
            return $this->success(null, 'you have logged out successfully');
        }
    }
}
