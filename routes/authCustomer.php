<?php

use App\Http\Controllers\CustomerAuth\AuthenticatedSessionController;
use App\Http\Controllers\CustomerAuth\EmailVerificationNotificationController;
use App\Http\Controllers\CustomerAuth\NewPasswordController;
use App\Http\Controllers\CustomerAuth\PasswordResetLinkController;
use App\Http\Controllers\CustomerAuth\RegisteredUserController;
use App\Http\Controllers\CustomerAuth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

route::group(['prefix' => 'customers', 'as' => 'customers.'], function () {

    route::group(['middleware' => 'guest'] , function () {
        Route::post('/register', [RegisteredUserController::class, 'store'])
            ->name('register');

        Route::post('/login', [AuthenticatedSessionController::class, 'store'])
            ->name('login');

        Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
            ->name('password.email');

        Route::post('/reset-password', [NewPasswordController::class, 'store'])
            ->name('password.store');
    });

    route::group(['middleware' => 'auth:api-customer'] , function () {
        Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
            ->middleware(['signed', 'throttle:6,1'])
            ->name('verification.verify');

        Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
            ->middleware('throttle:6,1')
            ->name('verification.send');

        Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
            ->name('logout');
    });
});
