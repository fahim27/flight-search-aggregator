<?php

use App\Http\Controllers\Api\FlightBookingController;
use App\Http\Controllers\Api\FlightController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('flights')->group(function () {
    Route::get('search', [FlightController::class, 'search']);

    Route::controller(FlightBookingController::class)->group(function () {
        Route::post('bookings', 'storeBooking');
        Route::get('bookings/{reference}', 'showBooking');
    });
});


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
