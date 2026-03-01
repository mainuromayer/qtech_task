<?php

use App\Http\Controllers\API\UserAuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\V1\JobCategoryController;
use App\Http\Controllers\API\V1\JobController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\ApplicationController;


Route::controller(UserAuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');

    // Resend Otp
    Route::post('resend-otp', 'resendOtp');

    // Forget Password
    Route::post('forgot-password', 'forgotPassword');
    Route::post('verify-otp-password', 'varifyOtpWithOutAuth');
    Route::post('reset-password', 'resetPassword');

    // Google Login
// Route::post('google/login', 'googleLogin');
});


Route::group(['middleware' => ['jwt.verify']], function () {
    Route::post('logout', [UserAuthController::class, 'logout']);
    Route::get('me', [UserAuthController::class, 'me']);
    Route::post('refresh', [UserAuthController::class, 'refresh']);

    Route::delete('/delete/user', [UserController::class, 'deleteUser']);

    Route::post('password/change', [UserController::class, 'changePassword']);
    Route::post('profile/update', [UserController::class, 'updateProfile']);

});



Route::get('job_categories', [JobCategoryController::class, 'index']);
Route::get('jobs', [JobController::class, 'index']);
Route::get('jobs/{id}', [JobController::class, 'show']);
Route::post('applications', [ApplicationController::class, 'store']);

// admin for job create and delete
Route::group(['prefix' => 'admin', 'middleware' => ['auth:api']], function () {
    Route::post('jobs', [JobController::class, 'store']);
    Route::delete('jobs/{id}', [JobController::class, 'destroy']);
});
