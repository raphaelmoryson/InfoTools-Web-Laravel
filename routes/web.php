<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\CrmController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\CustomerController;
use App\Http\Middleware\CommercialMiddleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// --- PAGE D'ACCUEIL ---
Route::get('/', [HomeController::class, 'index'])
    ->name('home')
    ->middleware('auth');

// --- AUTHENTIFICATION MANUELLE (AD/LDAP plus tard) ---
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// --- LOGOUT ---
Route::post('/logout', function () {
    Auth::logout();
    return redirect()->route('login');
})->name('logout');

// --- CRM (espace commerciaux) ---
Route::prefix('crm')->middleware(['auth', CommercialMiddleware::class])->group(function () {
    Route::get('/', [CrmController::class, 'dashboard'])->name('crm.dashboard');
    Route::get('/products', [ProductController::class, 'index'])->name('crm.products');
    Route::get('/client/purchases', [CustomerController::class, 'purchases'])->name('crm.client.purchases');
});

// --- CLIENTS ---
Route::middleware('auth')->group(function () {
    Route::get('/clients', [CustomerController::class, 'index'])->name('customer.index');
    Route::get('/clients/create', [CustomerController::class, 'create'])->name('customer.create');
    Route::post('/clients', [CustomerController::class, 'store'])->name('customer.store');
    Route::get('/clients/{customer}', [CustomerController::class, 'show'])->name('customer.show');
    Route::get('/clients/{customer}/edit', [CustomerController::class, 'edit'])->name('customer.edit');
    Route::put('/clients/{customer}', [CustomerController::class, 'update'])->name('customer.update');
    Route::delete('/clients/{customer}', [CustomerController::class, 'destroy'])->name('customer.destroy');
    
});

// --- APPOINTMENTS / PRODUCTS / INVOICES (Commerciaux uniquement) ---
Route::resource('appointments', AppointmentController::class);
Route::resource('products', ProductController::class);
Route::resource('invoices', InvoiceController::class);


// --- DASHBOARD (Jetstream/Livewire) ---
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
