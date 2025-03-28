<?php

use App\Http\Controllers\Admin\SupporterController as AdminSupporterController;
use App\Http\Controllers\Admin\BankFormsController;
use App\Http\Controllers\Admin\DonationAgreementController;
use App\Http\Controllers\Admin\DonationAgreementsController;
use App\Http\Controllers\Admin\TargetDonationsController;
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
        
        // Donation Agreements routes
        Route::get('donation-agreements/print', [DonationAgreementsController::class, 'print'])
            ->name('donation-agreements.print');
        Route::get('donation-agreements/export', [DonationAgreementsController::class, 'export'])
            ->name('donation-agreements.export');
        
        Route::resource('donation-agreements', DonationAgreementsController::class)
            ->names('donation-agreements');
        
        // Target Donations routes
        Route::get('target-donations/print', [TargetDonationsController::class, 'print'])
            ->name('target-donations.print');
        Route::get('target-donations/{targetDonation}/print', [TargetDonationsController::class, 'printShow'])
            ->name('target-donations.print-show');
        Route::get('target-donations/export', [TargetDonationsController::class, 'export'])
            ->name('target-donations.export');
        Route::resource('target-donations', TargetDonationsController::class);
    });
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';


