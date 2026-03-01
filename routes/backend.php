<?php

use App\Http\Controllers\Web\Backend\Blog\BlogController;
use App\Http\Controllers\Web\Backend\DashboardController;
use App\Http\Controllers\Web\Backend\Settings\MailSettingController;
use App\Http\Controllers\Web\Backend\Settings\ProfileController;
use App\Http\Controllers\Web\Backend\Settings\SystemSettingController;
use App\Http\Controllers\Web\Backend\User\UserController;
use App\Http\Controllers\Web\Backend\V1\Job\ApplicationController;
use App\Http\Controllers\Web\Backend\V1\Job\JobCategoryController;
use App\Http\Controllers\Web\Backend\V1\Job\JobController;
use Illuminate\Support\Facades\Route;



Route::middleware('auth')->group(function () {


    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


    //Route for SystemSettingController
    Route::get('/system-setting', [SystemSettingController::class, 'index'])->name('settings.system.index');
    Route::post('/system-setting/', [SystemSettingController::class, 'update'])->name('settings.system.update');

    //Route for ProfileController
    Route::get('/profile', [ProfileController::class, 'showProfile'])->name('settings.profile');
    Route::post('/update-profile', [ProfileController::class, 'updateProfile'])->name('settings.update-profile');
    Route::post('/update-profile-password', [ProfileController::class, 'updatePassword'])->name('settings.update-password');

    //Route for MailSettingController
    Route::get('/mail-setting', [MailSettingController::class, 'index'])->name('settings.mail');
    Route::post('/mail-setting', [MailSettingController::class, 'update'])->name('settings.mail-update');


    //Route for Blog Controller
    Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
    Route::get('/blog/create', [BlogController::class, 'create'])->name('blog.create');
    Route::post('/blog/store', [BlogController::class, 'store'])->name('blog.store');
    Route::get('/blog/show/{id}', [BlogController::class, 'show'])->name('blog.show');
    Route::get('/blog/edit/{id}', [BlogController::class, 'edit'])->name('blog.edit');
    Route::post('/blog/update/{id}', [BlogController::class, 'update'])->name('blog.update');
    Route::get('/blog/status/{id}', [BlogController::class, 'status'])->name('blog.status');
    Route::delete('/blog/destroy/{id}', [BlogController::class, 'destroy'])->name('blog.destroy');

    //Route for User Controller
    Route::get('/users', [UserController::class, 'index'])->name('user.index');
    Route::get('/users/data', [UserController::class, 'getData'])->name('user.data');

    Route::get('/user/status/{id}', [UserController::class, 'status'])->name('user.status');

    Route::resource('user', UserController::class)->except(['index']);



    Route::controller(JobCategoryController::class)->prefix('job_category')->group(function () {
        Route::get('/', 'index')->name('job_category.index');
        Route::get('/create', 'create')->name('job_category.create');
        Route::post('/store', 'store')->name('job_category.store');
        Route::get('/edit/{id}', 'edit')->name('job_category.edit');
        Route::post('/update/{id}', 'update')->name('job_category.update');
        Route::get('/status/{id}', 'status')->name('job_category.status');
        Route::delete('/destroy/{id}', 'destroy')->name('job_category.destroy');
    });

    Route::controller(JobController::class)->prefix('job')->group(function () {
        Route::get('/', 'index')->name('job.index');
        Route::get('/create', 'create')->name('job.create');
        Route::post('/store', 'store')->name('job.store');
        Route::get('/edit/{id}', 'edit')->name('job.edit');
        Route::post('/update/{id}', 'update')->name('job.update');
        Route::get('/status/{id}', 'status')->name('job.status');
        Route::delete('/destroy/{id}', 'destroy')->name('job.destroy');
    });

    Route::controller(ApplicationController::class)->prefix('job_application')->group(function () {
        Route::get('/', 'index')->name('job.application.index');
        Route::get('/data', 'getData')->name('job.application.data');
        Route::get('/show/{id}', 'show')->name('job.application.show');
        Route::delete('/destroy/{id}', 'destroy')->name('job.application.destroy');
    });
});
