<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Pages\User\DashboardController;
use App\Http\Controllers\Pages\User\NewViolatorController;
use App\Http\Controllers\Pages\User\ExistingViolatorController;
use App\Http\Controllers\Pages\User\TicketHistoryController;

Route::group(['middleware' => 'auth'], function () {

	Route::group(['middleware' => 'user'], function () {

		Route::group(['prefix' => 'user'], function () {
			Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
			Route::get('/dashboard/search', [DashboardController::class, 'searchExistingViolator'])->name('search-exising-violator');
			
			Route::get('/new-violator', [NewViolatorController::class, 'newViolator'])->name('new-violator');
			Route::post('/new-violator', [NewViolatorController::class, 'createNewViolator'])->name('create-new-violator');

			Route::get('/existing-violator/{id}', [ExistingViolatorController::class, 'existingViolator'])->name('existing-violator');
			Route::post('/new-violation', [ExistingViolatorController::class, 'createNewViolation'])->name('create-new-violation');

			Route::get('/ticket-history', [TicketHistoryController::class, 'ticketHistory'])->name('user.ticket-history');
			Route::get('/ticket-history/search', [TicketHistoryController::class, 'searchYear'])->name('user.search-year');

			Route::get('/regions', [NewViolatorController::class, 'getRegions']);
			Route::get('/provinces', [NewViolatorController::class, 'getProvinces']);
			Route::get('/municipals', [NewViolatorController::class, 'getMunicipals']);
			Route::get('/barangays', [NewViolatorController::class, 'getBarangays']);

		});

	});
});

?>