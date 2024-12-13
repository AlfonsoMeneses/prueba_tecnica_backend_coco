<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ResourceController;
use App\Http\Controllers\Api\ReservationController;

/** Recursos */
//Obtener todos los recursos
Route::get('/resources', [ResourceController::class, 'getAll']);
//Obtener todos los recursos disponibles en un rango de fechas
Route::get('/resources/availability', [ResourceController::class, 'getAvailableResources']);
//Obtener la disponibilidad de un recurso en un rango de fechas
Route::get('/resources/{id}/availability', [ResourceController::class, 'getResourceAvailability']);
//Creación de un recurso
Route::post('/resources', [ResourceController::class, 'create']);

/** Reservas */
//Creación de una reservación
Route::post('/reservations', [ReservationController::class, 'create']);
//Cancelar una reservación
Route::delete('/reservations/{id}', [ReservationController::class, 'cancel']);