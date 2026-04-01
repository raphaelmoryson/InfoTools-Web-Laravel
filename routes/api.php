<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CustomerApiController;
use App\Http\Controllers\Api\AppointmentApiController;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\InvoiceApiController;
use App\Http\Controllers\Api\DashboardApiController;
use App\Http\Middleware\CommercialMiddleware;

// routes/api.php
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Ajoute le middleware commercial ici pour protéger les clients
    Route::middleware('commercial')->group(function () {
        Route::apiResource('clients-api', CustomerApiController::class)
            ->parameters(['clients-api' => 'customer']) // <--- AJOUTE CETTE LIGNE
            ->only(['index', 'show', 'store', 'update', 'destroy']);
    });

    Route::apiResource('appointments-api', AppointmentApiController::class);

    Route::apiResource('products-api', ProductApiController::class)
        ->parameters(['products-api' => 'product']);

    Route::apiResource('invoices-api', InvoiceApiController::class);

    Route::get('/stats/dashboard-api', DashboardApiController::class);
});