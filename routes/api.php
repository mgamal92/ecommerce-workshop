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

Route::middleware(['auth:api-user'])->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::get('categories/{id}/products', [CategoryController::class, 'showWithProducts']);
    Route::resource('products', ProductController::class);
    Route::resource('checkout', CheckoutController::class);
    Route::resource('payments', PaymentController::class);
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
});


Route::middleware(['auth:customer,api-customer'])->group(function () {
    Route::get('products/search/{query}', [ProductController::class, 'search']);

    Route::resource('carts', CartController::class);
    Route::post('carts/add-to-cart/{product_id}', [CartController::class, 'addToCart']);
    Route::post('carts/update-cart/{product_id}', [CartController::class, 'updateCart']);
    Route::post('carts/remove-from-cart/{product_id}', [CartController::class, 'removeFromCart']);
    Route::post('carts/clear', [CartController::class, 'clear']);
});


require __DIR__ . '/auth.php';
require __DIR__ . '/authCustomer.php';