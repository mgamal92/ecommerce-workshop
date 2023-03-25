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
use App\Http\Controllers\StaffController;
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
    Route::resource('categories', CategoryController::class)->except('destroy');
    Route::get('categories/{id}/products', [CategoryController::class, 'showWithProducts']);
    Route::resource('products', ProductController::class)->except('destroy');
    Route::resource('checkout', CheckoutController::class);
    Route::resource('invoices', InvoiceController::class);
    Route::resource('staff/user', StaffController::class)->except(['destroy', 'create']);


    //user roles
    Route::middleware(['role:super-admin'])->group(function () {

        Route::resource('user/roles', AdminRolesController::class);

        Route::controller(AdminRolesController::class)->group(function () {

            Route::post('assign-role/users/{user}/roles/{role}', 'assignRole');

            Route::post('remove-role/users/{user}/roles/{role}', 'dropRole');
        });
    });

    Route::middleware(['role:admin'])->group(function () {
        Route::resource('products', ProductController::class)->only('destroy');
        Route::resource('categories', CategoryController::class)->only('destroy');
        Route::resource('staff/user', StaffController::class)->only(['destroy', 'create']);
        Route::resource('orders', OrderController::class);

        //shared between customer and user of admin-role
        Route::controller(CustomerController::class)->prefix('admin/customers/')->group(function () {
            Route::get('list-all', 'index')->name('customers.index');
            Route::get('show/{customer}', 'show');
            Route::put('update/{customer}', 'update');
            Route::delete('delete/{customer}', 'destroy');
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

Route::middleware(['auth:customer,api-customer'])->group(function () {
    //search in products
    Route::get('products/search/{query}', [ProductController::class, 'search']);

    Route::resource('carts', CartController::class);
    Route::post('carts/add-to-cart/{product_id}', [CartController::class, 'addToCart'])->name('add.cart');
    Route::post('carts/update-cart/{product_id}', [CartController::class, 'updateCart']);
    Route::post('carts/remove-from-cart/{product_id}', [CartController::class, 'removeFromCart']);
    Route::post('carts/clear', [CartController::class, 'clear']);
    Route::resource('customers', CustomerController::class)->except('index');
    Route::get('checkout', [CheckoutController::class, 'index'])->name('checkout');
});


require __DIR__ . '/auth.php';
require __DIR__ . '/authCustomer.php';
