<?php

use Illuminate\Http\Request;
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

Route::group(['middleware'=>'guest'], function(){
    Route::post("login", [App\Http\Controllers\Api\AuthController::class, 'login']);
    Route::post("register", [App\Http\Controllers\Api\AuthController::class, 'register']);
});

Route::middleware("auth:api")->group(function(){
    Route::post('/logout', [App\Http\Controllers\Api\AuthController::class, 'logout']);
    Route::get("userinfo", [App\Http\Controllers\Api\AuthController::class, 'userInfo']);
    Route::get("all-tenants", [App\Http\Controllers\Api\UserController::class, 'allTenants']);
    Route::post("createrole", [App\Http\Controllers\Api\RoleController::class, 'store']);
    Route::get("allroles", [App\Http\Controllers\Api\RoleController::class, 'show']);
    Route::post("create-tenant", [App\Http\Controllers\Api\UserController::class, 'createTenant']);
    Route::post('update-tenant', [App\Http\Controllers\Api\UserController::class, 'updateTenants']);
    Route::post('delete-tenant', [App\Http\Controllers\Api\UserController::class, 'destroyTenant']);
    Route::post('createcustomer', [App\Http\Controllers\Api\CustomerController::class, 'createCustomer']);
    Route::post('getcustomer', [App\Http\Controllers\Api\CustomerController::class, 'getCustomer']);
    Route::post('updatecustomer', [App\Http\Controllers\Api\CustomerController::class, 'updateCustomer']);
    Route::post('findcustomer', [App\Http\Controllers\Api\UserController::class, 'findCustomer']);
});
