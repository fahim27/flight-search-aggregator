<?php

use App\Http\Controllers\Api\FlightController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::controller(FlightController::class)->prefix('flights')->group(function () {
    Route::get('search', 'search');
    Route::post('bookings', 'book');
});


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
