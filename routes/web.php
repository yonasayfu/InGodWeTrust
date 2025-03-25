<?php

use App\Http\Controllers\Admin\SupporterController as AdminSupporterController;
use App\Http\Controllers\Admin\BankFormsController;
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
        // Supporters routes
        Route::get('supporters/print', [AdminSupporterController::class, 'printIndex'])
            ->name('supporters.print');
        Route::get('supporters/export', [AdminSupporterController::class, 'export'])
            ->name('supporters.export');
        Route::resource('supporters', AdminSupporterController::class);
        Route::get('supporters/{supporter}/print', [AdminSupporterController::class, 'printShow'])
            ->name('supporters.print-show');
        
        // Bank Forms routes
        Route::resource('bank-forms', BankFormsController::class);
        Route::get('bank-forms/{bankForm}/download', [BankFormsController::class, 'download'])
            ->name('bank-forms.download');
    });
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
