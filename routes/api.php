<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\CabController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\ContactController;

/*
|--------------------------------------------------------------------------
| API Routes  — prefix: /api
|--------------------------------------------------------------------------
*/

// Cabs
Route::get('/homepage',          [HomeController::class, 'index']);
Route::get('/cabs',              [CabController::class, 'index']);
Route::get('/cabs/search',       [CabController::class, 'search']); 

// Services (routes/outstation pages)
Route::get('/services',          [ServiceController::class, 'index']);
Route::get('/services/{slug}',   [ServiceController::class, 'show']);

// Booking
Route::post('/bookings',         [BookingController::class, 'store']);

// Contact
Route::post('/contact',          [ContactController::class, 'store']);
