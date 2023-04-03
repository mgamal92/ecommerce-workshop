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
use App\Permissions\PermissionsList;
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


//routes for all users
Route::middleware(['auth:api-user,api-customer'])->group(function () {

    //categories routes
    Route::middleware('permission:'.PermissionsList::LIST_CATEGORIES)->prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::get('/{category}', [CategoryController::class, 'show']);
        Route::get('/{id}/products', [CategoryController::class, 'showWithProducts']);
    });

    //products routes
    Route::middleware('permission:'.PermissionsList::LIST_PRODUCTS)->prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/{product}', [ProductController::class, 'show']);
        Route::get('/search/{query}', [ProductController::class, 'search']);
    });

});


//routes for admin users
Route::middleware(['auth:api-user'])->group(function () {

    //categories routes
    Route::prefix('categories')->controller(CategoryController::class)->group(function () {
        Route::post('/', 'store')->middleware('permission:'.PermissionsList::CREATE_CATEGORIES);
        Route::put('/{category}', 'update')->middleware('permission:'.PermissionsList::UPDATE_CATEGORIES);
        Route::delete('/{category}', 'destroy')->middleware('permission:'.PermissionsList::DELETE_CATEGORIES);
    });

    //products routes
    Route::prefix('products')->controller(ProductController::class)->group(function () {
        Route::post('/', 'store')->middleware('permission:'.PermissionsList::CREATE_PRODUCTS);
        Route::put('/{product}', 'update')->middleware('permission:'.PermissionsList::UPDATE_PRODUCTS);
        Route::delete('/{product}', 'destroy')->middleware('permission:'.PermissionsList::DELETE_PRODUCTS);
        Route::post('/import-csv-file', 'importCsvFile')->middleware('permission:'.PermissionsList::IMPORT_CSV_PRODUCTS);
    });

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

//routes for cusstomers
Route::middleware(['auth:api-customer'])->group(function () {
    Route::resource('carts', CartController::class);
    Route::post('carts/add-to-cart/{product_id}', [CartController::class, 'addToCart']);
    Route::post('carts/update-cart/{product_id}', [CartController::class, 'updateCart']);
    Route::post('carts/remove-from-cart/{product_id}', [CartController::class, 'removeFromCart']);
    Route::post('carts/clear', [CartController::class, 'clear']);
});


require __DIR__ . '/auth.php';
require __DIR__ . '/authCustomer.php';
