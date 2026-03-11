<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CustomerApiController;
use App\Http\Controllers\Api\AppointmentApiController;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\InvoiceApiController;
use App\Http\Controllers\Api\DashboardApiController;
use App\Http\Middleware\CommercialMiddleware;

/*
|--------------------------------------------------------------------------
| Auth API
|--------------------------------------------------------------------------
*/
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum');

/*
|--------------------------------------------------------------------------
| API protégée (commercial uniquement)
|--------------------------------------------------------------------------
*/

Route::apiResource('clients-api', CustomerApiController::class)
    ->only(['index', 'show', 'store', 'update', 'destroy']);

// // Rendez-vous
Route::apiResource('appointments-api', AppointmentApiController::class);

// Produits
Route::apiResource('products-api', ProductApiController::class);

// Factures
Route::apiResource('invoices-api', InvoiceApiController::class);

// Dashboard / stats
Route::get('/stats/dashboard-api', DashboardApiController::class);
