<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ResourceController;
use App\Http\Controllers\Api\ReservationController;

/** Recursos */
Route::get('/resources', [ResourceController::class, 'getAll']);
Route::get('/resources/{id}/availability', [ResourceController::class, 'getResourceAvailability']);
Route::post('/resources', [ResourceController::class, 'create']);

/** Reservas */
Route::post('/reservations', [ReservationController::class, 'create']);
Route::delete('/reservations/{id}', [ReservationController::class, 'cancel']);