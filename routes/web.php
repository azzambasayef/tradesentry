<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\RiskController;
use App\Http\Controllers\NewsController;

Route::get('/', function () {
    return redirect('/dashboard');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Country Dashboard
    Route::get('/countries/{id}', [CountryController::class, 'show'])->name('countries.show');
    Route::get('/countries', [CountryController::class, 'index'])->name('countries.index');
    Route::get('/currencies', [CurrencyController::class, 'index'])->name('currencies.index');
    Route::get('/risk', [RiskController::class, 'index'])->name('risk.index');
    Route::post('/risk/recalculate', [RiskController::class, 'recalculate'])->name('risk.recalculate');
    Route::get('/news', [NewsController::class, 'index'])->name('news.index');
    Route::post('/news/fetch', [NewsController::class, 'fetch'])->name('news.fetch');
    Route::get('/compare', [App\Http\Controllers\ComparisonController::class, 'index'])->name('compare.index');
    Route::post('/api/compare', [App\Http\Controllers\ComparisonController::class, 'compare'])->name('compare.fetch');

    // AJAX Endpoint for fetching all countries
    Route::get('/api/countries', function () {
        return response()->json(\App\Models\Country::with('riskScore')->get());
    });

    // REST API Endpoint for fetching all ports
    Route::get('/api/ports', [App\Http\Controllers\PortController::class, 'api'])->name('api.ports');
    
    // REST API Endpoint for fetching live ships
    Route::get('/api/ships', [App\Http\Controllers\PortController::class, 'apiShips'])->name('api.ships');
});
