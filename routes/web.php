<?php

use App\Http\Controllers\ConceptController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Routes d'authentification Breeze
require __DIR__.'/auth.php';

// Routes de profil (manquantes !)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Vos routes protégées
Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    Route::resource('domains', DomainController::class);
    
    Route::get('domains/{domain}/concepts', [ConceptController::class, 'index'])->name('domains.concepts.index');
    Route::get('domains/{domain}/concepts/create', [ConceptController::class, 'create'])->name('domains.concepts.create');
    Route::post('domains/{domain}/concepts', [ConceptController::class, 'store'])->name('domains.concepts.store');
    Route::get('domains/{domain}/concepts/{concept}', [ConceptController::class, 'show'])->name('domains.concepts.show');
    Route::get('domains/{domain}/concepts/{concept}/edit', [ConceptController::class, 'edit'])->name('domains.concepts.edit');
    Route::put('domains/{domain}/concepts/{concept}', [ConceptController::class, 'update'])->name('domains.concepts.update');
    Route::delete('domains/{domain}/concepts/{concept}', [ConceptController::class, 'destroy'])->name('domains.concepts.destroy');
    Route::patch('domains/{domain}/concepts/{concept}/status', [ConceptController::class, 'updateStatus'])->name('domains.concepts.status');
    
});