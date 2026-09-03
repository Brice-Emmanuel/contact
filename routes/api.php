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
Route::post('/register', [authController::class, 'register']);
Route::post('/login', [authController::class, 'login'])->name('login');


// --- 2. ROUTES PROTÉGÉES (Nécessitent le Token Bearer) ---
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [authController::class, 'logout']);

    // --- GESTION DES CONTACTS ---
    Route::prefix('contacts')->group(function () {
        // La création de contact DOIT être ici pour que auth()->id() fonctionne !
        Route::post('/', [contactController::class, 'store']);
        Route::get('/', [contactController::class, 'index']);
        
        // Vos autres routes...
        Route::get('/anniversaire', [contactController::class, 'anniversaire']);
        Route::get('/anniversairedans7jours', [contactController::class, 'anniversaire_prevu']);
        Route::get('/statistiques/count', [contactController::class, 'statistiques']);
        Route::put('/favoris/{id}', [contactController::class, 'favoris']);
        Route::put('/restauration/{id}', [contactController::class, 'restauration']);
        Route::delete('/supprimerdeff/{id}', [contactController::class, 'forceDestroy']);
        Route::get('/{id}', [contactController::class, 'show']);
        Route::put('/{id}', [contactController::class, 'update']);
        Route::delete('/{id}', [contactController::class, 'destroy']);
    });

});