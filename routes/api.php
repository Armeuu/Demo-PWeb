<?php

use App\Http\Controllers\MediaController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - FlipPlay REST API (Demo Modul 4)
|--------------------------------------------------------------------------
*/

// 1. CRUD Media (index, store, show, update, destroy)
Route::apiResource('media', MediaController::class)->parameters(['media' => 'media']);

// 2. CRUD User / Pengguna
Route::apiResource('users', UserController::class)->only(['index', 'store', 'show']);

// 3. Custom Actions: Manajemen Relasi Koleksi / Watchlist Pengguna (belongsToMany)
Route::prefix('users/{user}')->group(function () {
    Route::get('collection', [UserController::class, 'getCollection']);
    Route::post('collection', [UserController::class, 'addToCollection']);
    Route::patch('collection/{media}', [UserController::class, 'updateCollectionStatus']);
    Route::delete('collection/{media}', [UserController::class, 'removeFromCollection']);
});
