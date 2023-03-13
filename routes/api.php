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
    Route::post("forgot-password", [App\Http\Controllers\Api\AuthController::class, 'forgotPassword']);
    Route::post("reset-password", [App\Http\Controllers\Api\AuthController::class, 'resetPassword']);
    Route::get('generate-pdf', [App\Http\Controllers\Api\CertificateController::class, 'generatePdf']);
    Route::post('student-record', [App\Http\Controllers\Api\CertificateController::class, 'studentInfo']);
});

Route::middleware("auth:api")->group(function(){
    Route::post('/logout', [App\Http\Controllers\Api\AuthController::class, 'logout']);
    Route::get("userinfo", [App\Http\Controllers\Api\AuthController::class, 'userInfo']);
    Route::post("update-profile", [App\Http\Controllers\Api\AuthController::class, 'updateProfile']);
    Route::post("profile-password", [App\Http\Controllers\Api\AuthController::class, 'profilePassword']);
    Route::get("all-tenants", [App\Http\Controllers\Api\UserController::class, 'index']);
    Route::post("create-tenant", [App\Http\Controllers\Api\UserController::class, 'store']);
    Route::post('update-tenant', [App\Http\Controllers\Api\UserController::class, 'update']);
    Route::post('delete-tenant', [App\Http\Controllers\Api\UserController::class, 'destroy']);
    Route::post('create-customer', [App\Http\Controllers\Api\CustomerController::class, 'store']);
    Route::get("all-customers", [App\Http\Controllers\Api\CustomerController::class, 'index']);
    Route::post('update-customer', [App\Http\Controllers\Api\CustomerController::class, 'update']);
    Route::post('delete-customer', [App\Http\Controllers\Api\CustomerController::class, 'destroy']);
    Route::post('create-course', [App\Http\Controllers\Api\CourseController::class, 'store']);
    Route::get("all-courses", [App\Http\Controllers\Api\CourseController::class, 'index']);
    Route::post('update-course', [App\Http\Controllers\Api\CourseController::class, 'update']);
    Route::post('delete-course', [App\Http\Controllers\Api\CourseController::class, 'destroy']);
    Route::post('create-tenant-courses', [App\Http\Controllers\Api\CourseController::class, 'createTenantCourses']);
    Route::post("tenant-course", [App\Http\Controllers\Api\CourseController::class, 'tenantCourse']);
    Route::get("student-courses", [App\Http\Controllers\Api\StudentController::class, 'studentCourses']);
    Route::post('create-student', [App\Http\Controllers\Api\StudentController::class, 'store']);
    Route::get("all-students", [App\Http\Controllers\Api\StudentController::class, 'index']);
    Route::post('update-student', [App\Http\Controllers\Api\StudentController::class, 'update']);
    Route::post('delete-student', [App\Http\Controllers\Api\StudentController::class, 'destroy']);
    Route::post('upload-student-csv', [App\Http\Controllers\Api\StudentController::class, 'importStudentCsv']);
    Route::post('create-certificate', [App\Http\Controllers\Api\CertificateController::class, 'store']);
    Route::get("all-certificates", [App\Http\Controllers\Api\CertificateController::class, 'index']);
    Route::post('update-certificate', [App\Http\Controllers\Api\CertificateController::class, 'update']);
    Route::post('delete-certificate', [App\Http\Controllers\Api\CertificateController::class, 'destroy']);
});
