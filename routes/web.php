<?php

use App\Http\Controllers\ApprenantController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ApprenantController::class, 'index'])->name('home');
Route::get('/apprenants', [ApprenantController::class, 'index'])->name('apprenants.index');

Route::post('/apprenants', [ApprenantController::class, 'store'])->name('apprenants.store');
Route::post('/groupes/generer', [ApprenantController::class, 'genererGroupes'])->name('groupes.generer');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';