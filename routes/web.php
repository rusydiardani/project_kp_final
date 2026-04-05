<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ServiceRequestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});


Auth::routes(['register' => false]);

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    // Services CRUD
    Route::resource('services', ServiceRequestController::class)->except(['create', 'edit', 'show']);
    Route::post('services/{service}/pickup', [ServiceRequestController::class, 'markAsPickedUp'])->name('services.pickup');
    Route::delete('services-bulk-delete', [ServiceRequestController::class, 'bulkDestroy'])->name('services.bulkDelete');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');

    // Admin Only Routes
    // Admin Only Routes
    Route::middleware(['can:admin'])->group(function () {
        Route::resource('users', UserController::class);

    });
});

// Fallback redirect for /home
Route::get('/home', function () {
    return redirect()->route('dashboard');
});


