<?php

use App\Http\Controllers\Admin\SupporterController as AdminSupporterController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::prefix('dashboard')->group(function () {
        // Define print and export routes FIRST
        Route::get('supporters/print', [AdminSupporterController::class, 'printIndex'])
            ->name('supporters.print');
        Route::get('supporters/export', [AdminSupporterController::class, 'export'])
            ->name('supporters.export');
        
        // Then define the resource route
        Route::resource('supporters', AdminSupporterController::class);
        
        // Keep show print route after resource
        Route::get('supporters/{supporter}/print', [AdminSupporterController::class, 'printShow'])
            ->name('supporters.print-show');
    });
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
