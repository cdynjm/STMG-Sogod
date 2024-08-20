<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Pages\Auth\LoginController;


use App\Http\Controllers\Pages\Admin\DashboardController;
use App\Http\Controllers\Pages\Admin\StaffController;
use App\Http\Controllers\Pages\Admin\ViolationController;

Route::group(['middleware' => 'guest'], function () {
	Route::get('/', [LoginController::class, 'login'])->name('login');
	Route::get('/login', [LoginController::class, 'login'])->name('login');
	Route::post('/authenticate', [LoginController::class, 'authenticate'])->name('login.authenticate');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/logout', [LoginController::class, 'destroy'])->name('logout');
});