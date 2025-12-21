<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [\App\Http\Controllers\PublicController::class, 'index'])->name('public.home');
Route::get('/alat/{alat}', [\App\Http\Controllers\PublicController::class, 'show'])
    ->name('alat.show');
Route::get('/alat/{alat}/availability', [\App\Http\Controllers\PublicController::class, 'checkAvailability'])
    ->name('alat.availability');
Route::get('/booking', [\App\Http\Controllers\PublicController::class, 'bookingForm'])
    ->name('booking.form');

Route::post('/booking', [\App\Http\Controllers\PublicController::class, 'storeBooking'])
    ->name('booking.store');

Route::get('/booking/receipt/{booking}', [\App\Http\Controllers\PublicController::class, 'downloadReceipt'])
    ->name('booking.receipt')
    ->middleware('signed');

Route::get('/booking/sukses', [\App\Http\Controllers\PublicController::class, 'success'])->name('booking.success');

// Admin routes (auth middleware)
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])
        ->name('dashboard');
    // Route Approve Booking 
    Route::get('/booking/approve/{id}', [\App\Http\Controllers\Admin\BookingController::class, 'approveBooking'])
        ->name('booking.approve');
    // Route Cancel
    Route::get('/booking/cancel/{id}', [\App\Http\Controllers\Admin\BookingController::class, 'cancel'])
        ->name('booking.cancel');
    // Route pick up confirmation
    Route::get('/booking/pickup/{id}', [\App\Http\Controllers\Admin\BookingController::class, 'pickupBooking'])
        ->name('booking.pickup');
    //Route Return Item
    Route::get('/booking/return/{id}', [\App\Http\Controllers\Admin\BookingController::class, 'returnBooking'])
        ->name('booking.return');


    Route::resource('/alat', \App\Http\Controllers\Admin\AlatController::class);

    Route::resource('/booking', \App\Http\Controllers\Admin\BookingController::class);
    // Route::get('/transaksi', [\App\Http\Controllers\Admin\TransaksiController::class, 'index'])
    //     ->name('transaksi.index');
});
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
