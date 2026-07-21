<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\RiskController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\WatchlistController;

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
    
    // Watchlist Routes
    Route::get('/watchlist', [WatchlistController::class, 'index'])->name('watchlist.index');
    Route::post('/watchlist/toggle/{country_id}', [WatchlistController::class, 'toggle'])->name('watchlist.toggle');

    // Admin Routes
    Route::middleware(['is_admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [\App\Http\Controllers\AdminController::class, 'index'])->name('index');
        
        Route::put('/users/{id}/role', [\App\Http\Controllers\AdminController::class, 'updateUserRole'])->name('users.role');
        Route::delete('/users/{id}', [\App\Http\Controllers\AdminController::class, 'deleteUser'])->name('users.delete');

        Route::post('/ports', [\App\Http\Controllers\AdminController::class, 'storePort'])->name('ports.store');
        Route::put('/ports/{id}', [\App\Http\Controllers\AdminController::class, 'updatePort'])->name('ports.update');
        Route::delete('/ports/{id}', [\App\Http\Controllers\AdminController::class, 'deletePort'])->name('ports.delete');

        Route::post('/articles', [\App\Http\Controllers\AdminController::class, 'storeArticle'])->name('articles.store');
        Route::put('/articles/{id}', [\App\Http\Controllers\AdminController::class, 'updateArticle'])->name('articles.update');
        Route::delete('/articles/{id}', [\App\Http\Controllers\AdminController::class, 'deleteArticle'])->name('articles.delete');
    });

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
