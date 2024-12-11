<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ResourceController;
use App\Http\Controllers\Api\ReservationController;

/** Recursos */
Route::get('/resources', [ResourceController::class, 'getAll']);
Route::post('/resources', [ResourceController::class, 'create']);

/** Reservas */
Route::post('/reservations', [ReservationController::class, 'create']);