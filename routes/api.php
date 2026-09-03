<?php

use App\Http\Controllers\authController;
use App\Http\Controllers\userController;
use App\Http\Controllers\contactController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// --- 1. ROUTES PUBLIQUES ---
Route::post('/register', [authController::class, 'register'])->name('api.register');
Route::post('/login', [authController::class, 'login'])->name('api.login');


// --- 2. ROUTES PROTÉGÉES (Nécessitent le Token Bearer) ---
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [authController::class, 'logout'])->name('api.logout');

    // --- GESTION DES CONTACTS ---
    Route::prefix('contacts')->name('api.contacts.')->group(function () {
        Route::post('/', [contactController::class, 'store'])->name('store');
        Route::get('/', [contactController::class, 'index'])->name('index');
        
        Route::get('/anniversaire', [contactController::class, 'anniversaire'])->name('anniversaire');
        Route::get('/anniversairedans7jours', [contactController::class, 'anniversaire_prevu'])->name('anniversaire.prevu');
        Route::get('/statistiques/count', [contactController::class, 'statistiques'])->name('stats');
        Route::put('/favoris/{id}', [contactController::class, 'favoris'])->name('favoris');
        Route::put('/restauration/{id}', [contactController::class, 'restauration'])->name('restauration');
        Route::delete('/supprimerdeff/{id}', [contactController::class, 'forceDestroy'])->name('force-delete');
        Route::get('/{id}', [contactController::class, 'show'])->name('show');
        Route::put('/{id}', [contactController::class, 'update'])->name('update');
        Route::delete('/{id}', [contactController::class, 'destroy'])->name('destroy');
    });

});