<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware(['web'])->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('/login', 'login')->name('login');
    });
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('/logout', 'logout')->name('logout');
    });

    Route::controller(UserController::class)->group(function () {
        Route::get('/getListUsersByCondition', 'getListUsersByCondition');
        Route::post('/deleteUser', 'deleteUser');
        Route::post('/updateActiveUser', 'updateActiveUser');
        Route::middleware(['web'])->group(function () {
            Route::post('/addUser', 'addUser');
            Route::post('/editUser', 'editUser');
        });
    });

    Route::controller(CustomerController::class)->group(function () {
        Route::get('/getListCustomersByCondition', 'getListCustomersByCondition');
        Route::post('/createCustomer', 'createCustomer');
        Route::get('/exportCustomerExcel', 'exportCustomerExcel');
        Route::post('/importCustomerExcel', 'importCustomerExcel');
    });

    Route::controller(ProductController::class)->group(function () {
        Route::get('/getListProductsByCondition', 'getListProductsByCondition');
        Route::get('/getDetailProduct', 'getDetailProduct');
        Route::post('/addProduct', 'addProduct');
        Route::post('/editProduct', 'editProduct');
    });
});
