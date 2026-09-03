<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Middleware\CheckContactLimit;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes Invités (Guest)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/', [AuthController::class, 'loginWeb']);

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'registerWeb']);

    Route::get('/forgot-password', [AuthController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

/*
|--------------------------------------------------------------------------
| Redirections de courtoisie
|--------------------------------------------------------------------------
*/
Route::redirect('/home', '/dashboard');

/*
|--------------------------------------------------------------------------
| Routes Protégées (Authentification requise)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Vérification de l'email
    Route::get('/email/verify', [AuthController::class, 'showVerificationNotice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmailWeb'])
        ->middleware('signed')
        ->name('verification.verify');
    Route::post('/email/resend', [AuthController::class, 'resendVerificationWeb'])->name('verification.resend');

    Route::post('/logout', [AuthController::class, 'logoutWeb'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | Routes Protégées (Auth + Email Vérifié)
    |--------------------------------------------------------------------------
    */
    Route::middleware('verified')->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | Gestion des Contacts
        |--------------------------------------------------------------------------
        */
        Route::prefix('contacts')->name('contacts.')->group(function () {

            // 1. Actions spécifiques (impérativement AVANT les routes avec paramètre dynamique {contact})
            Route::get('favoris', [ContactController::class, 'favoris'])->name('favoris');
            Route::get('trashed', [ContactController::class, 'trashed'])->name('trashed');
            Route::get('stats', [ContactController::class, 'stats'])->name('stats');
            Route::get('export', [ContactController::class, 'export'])->name('export');
            Route::post('import', [ContactController::class, 'import'])->middleware(CheckContactLimit::class)->name('import');

            // Sub-group Anniversaires
            Route::prefix('anniversaires')->name('anniversaires.')->group(function () {
                Route::get('aujourdhui', [ContactController::class, 'anniversairesAujourdhui'])->name('aujourdhui');
                Route::get('prochains', [ContactController::class, 'prochainsAnniversaires'])->name('prochains');
            });

            // 2. Actions sur un contact spécifique
            Route::post('{id}/toggle-favori', [ContactController::class, 'toggleFavori'])->name('toggle-favori');
            Route::post('{id}/restore', [ContactController::class, 'restore'])->name('restore');
            Route::delete('{id}/force-delete', [ContactController::class, 'forceDelete'])->name('force-delete');
        });

        // 3. Soumission avec contrôle du quota de contacts
        Route::post('/contacts', [ContactController::class, 'store'])
            ->middleware(CheckContactLimit::class)
            ->name('contacts.store');

        // 4. Resource du CRUD principal (index, create, show, edit, update, destroy)
        Route::resource('contacts', ContactController::class)->except(['store']);
    });
});