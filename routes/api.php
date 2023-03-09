<?php

use App\Http\Controllers\AdminRolesController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('carts', CartController::class);
    Route::resource('checkout', CheckoutController::class);
    Route::resource('invoices', InvoiceController::class);
    Route::resource('orders', OrderController::class);
    Route::resource('customers', CustomerController::class);

    //user roles
    Route::middleware(['role:super-admin'])->group(function () {

        Route::resource('user/roles', AdminRolesController::class);

        Route::controller(AdminRolesController::class)->group(function () {

            Route::post('assign-role/users/{user}/roles/{role}', 'assignRole');

            Route::post('remove-role/users/{user}/roles/{role}', 'dropRole');
        });
    });


    Route::controller(PaymentController::class)->prefix('payment/paymob/')->group(function () {

        // payment transaction callback
        Route::post('processing', 'processed');

        Route::prefix('callback/')->group(function () {

            //handle transaction callback
            Route::post('transaction', 'transaction');
            //handle response callback 
            Route::get('response', 'response');
        });
    });
});

Route::get('categories/{id}/products', [CategoryController::class, 'showWithProducts']);
