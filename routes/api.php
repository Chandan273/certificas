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
    Route::post("createrole", [App\Http\Controllers\Api\RoleController::class, 'store']);
    Route::get("allroles", [App\Http\Controllers\Api\RoleController::class, 'show']);
    Route::get("all-tenants", [App\Http\Controllers\Api\UserController::class, 'allTenants']);
    Route::post("create-tenant", [App\Http\Controllers\Api\UserController::class, 'createTenant']);
    Route::post('update-tenant', [App\Http\Controllers\Api\UserController::class, 'updateTenants']);
    Route::post('delete-tenant', [App\Http\Controllers\Api\UserController::class, 'destroyTenant']);
    Route::get("all-customers", [App\Http\Controllers\Api\CustomerController::class, 'allCustomers']);
    Route::post("create-customer", [App\Http\Controllers\Api\CustomerController::class, 'createCustomer']);
    Route::post('update-customer', [App\Http\Controllers\Api\CustomerController::class, 'updateCustomer']);
    Route::post('delete-customer', [App\Http\Controllers\Api\CustomerController::class, 'destroyCustomer']);
    Route::get("all-courses", [App\Http\Controllers\Api\CourseController::class, 'allCourses']);
    Route::post("create-course", [App\Http\Controllers\Api\CourseController::class, 'createCourse']);
    Route::post('update-course', [App\Http\Controllers\Api\CourseController::class, 'updateCourse']);
    Route::post('delete-course', [App\Http\Controllers\Api\CourseController::class, 'destroyCourse']);
    Route::get("all-students", [App\Http\Controllers\Api\StudentController::class, 'allStudents']);
    Route::post("create-student", [App\Http\Controllers\Api\StudentController::class, 'createStudent']);
    Route::post('update-student', [App\Http\Controllers\Api\StudentController::class, 'updateStudent']);
    Route::post('delete-student', [App\Http\Controllers\Api\StudentController::class, 'destroyStudent']);
});
