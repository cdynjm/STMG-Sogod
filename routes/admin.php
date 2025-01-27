<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Pages\Auth\LoginController;

use App\Http\Controllers\Pages\Admin\DashboardController;
use App\Http\Controllers\Pages\Admin\StaffController;
use App\Http\Controllers\Pages\Admin\ViolationController;
use App\Http\Controllers\Pages\Admin\UnpaidController;
use App\Http\Controllers\Pages\Admin\DriversController;
use App\Http\Controllers\Pages\Admin\DriversInfoController;
use App\Http\Controllers\Pages\Admin\TicketHistoryController;
use App\Http\Controllers\Pages\Admin\ReceiptController;
use App\Http\Controllers\Pages\Admin\ReceiptInfoController;

Route::group(['middleware' => 'auth'], function () {

	Route::group(['middleware' => 'admin'], function () {

		Route::group(['prefix' => 'admin'], function () {

			Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
			Route::get('/dashboard/analytics-search', [DashboardController::class, 'searchYearAnalytics'])->name('search-year-analytics');

			Route::get('/staff', [StaffController::class, 'staff'])->name('staff');
			Route::post('/create-staff', [StaffController::class, 'createStaff'])->name('create-staff');
			Route::patch('/update-staff', [StaffController::class, 'updateStaff'])->name('update-staff');
			Route::delete('/delete-staff', [StaffController::class, 'deleteStaff'])->name('delete-staff');

			Route::get('/violation', [ViolationController::class, 'violation'])->name('violation');
			Route::post('/violation', [ViolationController::class, 'createViolation'])->name('create-violation');
			Route::patch('/violation', [ViolationController::class, 'updateViolation'])->name('update-violation');
			Route::delete('/violation', [ViolationController::class, 'deleteViolation'])->name('delete-violation');

			Route::get('/unpaid', [UnpaidController::class, 'unpaid'])->name('unpaid');
			Route::patch('/paid-penalty', [UnpaidController::class, 'paidPenalty'])->name('paid-penalty');

			Route::get('/drivers', [DriversController::class, 'drivers'])->name('drivers');
			Route::get('/drivers-info/{id}', [DriversInfoController::class, 'driversInfo'])->name('drivers-info');

			Route::get('/ticket-history', [TicketHistoryController::class, 'ticketHistory'])->name('admin.ticket-history');
			Route::get('/ticket-history/search', [TicketHistoryController::class, 'searchYear'])->name('admin.search-year');

		//	Route::get('/receipt-info', [ReceiptInfoController::class, 'receiptInfo'])->name('receipt-info');
		//	Route::patch('/update-receipt-info', [ReceiptInfoController::class, 'updateReceiptInfo'])->name('update-receipt-info');
		//	Route::get('/receipt/{id}', [ReceiptController::class, 'receipt'])->name('admin.receipt');

			Route::get('/regions', [StaffController::class, 'getRegions']);
			Route::get('/provinces', [StaffController::class, 'getProvinces']);
			Route::get('/municipals', [StaffController::class, 'getMunicipals']);
			Route::get('/barangays', [StaffController::class, 'getBarangays']);


		});

	});

    Route::get('/logout', [LoginController::class, 'destroy'])->name('logout');
});

?>