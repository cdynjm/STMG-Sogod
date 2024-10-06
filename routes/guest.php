<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Pages\Auth\LoginController;
use App\Http\Controllers\Pages\Auth\PasswordResetController;

use App\Http\Controllers\Pages\Admin\DashboardController;
use App\Http\Controllers\Pages\Admin\StaffController;
use App\Http\Controllers\Pages\Admin\ViolationController;

Route::group(['middleware' => 'guest'], function () {
	Route::get('/', [LoginController::class, 'login'])->name('login');
	Route::get('/login', [LoginController::class, 'login'])->name('login');
	Route::post('/authenticate', [LoginController::class, 'authenticate'])->name('login.authenticate');

	Route::get('/password/reset', [PasswordResetController::class, 'request'])->name('password.request');
    Route::post('/password/email', [PasswordResetController::class, 'email'])->name('password.email');
    Route::get('/password/reset/{token}', [PasswordResetController::class, 'reset'])->name('password.reset');
    Route::post('/password/reset', [PasswordResetController::class, 'update'])->name('password.update');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/logout', [LoginController::class, 'destroy'])->name('logout');
});