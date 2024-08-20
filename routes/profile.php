<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Layouts\NavbarController;

Route::group(['middleware' => 'auth'], function () {
    Route::patch('/update-profile', [NavbarController::class, 'updateProfile'])->name('update-profile');
});

?>