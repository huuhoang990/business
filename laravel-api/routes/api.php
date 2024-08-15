<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => 'api', 'prefix' => 'user'], function () {
    Route::get('profile', [UserController::class, 'profile']);
    Route::post('register', [UserController::class, 'register']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    // Route::get('profile', [AuthController::class, 'profile']);

    /*
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('profile', 'AuthController@profile');
    */
});

Route::group([
    'middleware' => 'api',
], function ($router) {
    Route::get('province/{id}', [RegisterController::class, 'selectProvince']);
    Route::get('provinces', [RegisterController::class, 'getAllProvinces']);
    Route::get('district/{id}', [RegisterController::class, 'getDistByPCode']);
    Route::get('districts', [RegisterController::class, 'getAllDistricts']);
    Route::get('ward/{id}', [RegisterController::class, 'getWardByDCode']);
    // Route::get('wards', [RegisterController::class, 'getAllProvinces']);
    /*
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('profile', 'AuthController@profile');
    */
});
