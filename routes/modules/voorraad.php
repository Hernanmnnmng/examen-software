<?php

use App\Http\Controllers\Voorraad\CategoryController;
use App\Http\Controllers\Voorraad\ProductController;
use App\Http\Middleware\EnsureUserHasAnyRole;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Voorraadbeheer routes (merge-friendly module file)
|--------------------------------------------------------------------------
|
| This file is loaded from routes/web.php via a directory loader so teammates
| can add their own routes/modules without editing routes/web.php.
|
*/

Route::middleware(['auth'])->group(function () {
    // Producten: Directie + Magazijnmedewerker
    Route::middleware(EnsureUserHasAnyRole::class.':Directie,Magazijnmedewerker')
        ->prefix('voorraad')
        ->name('voorraad.')
        ->group(function () {
            Route::get('/', [ProductController::class, 'index'])->name('producten.index');
            Route::get('/producten/create', [ProductController::class, 'create'])->name('producten.create');
            Route::post('/producten', [ProductController::class, 'store'])->name('producten.store');
            Route::get('/producten/{id}/edit', [ProductController::class, 'edit'])->name('producten.edit');
            Route::put('/producten/{id}', [ProductController::class, 'update'])->name('producten.update');
            Route::delete('/producten/{id}', [ProductController::class, 'destroy'])->name('producten.destroy');
        });

    // Categorieën: Directie-only (existing alias middleware 'role' already registered)
    Route::middleware(['role:Directie'])
        ->prefix('voorraad/categorieen')
        ->name('voorraad.categorieen.')
        ->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->name('index');
            Route::get('/create', [CategoryController::class, 'create'])->name('create');
            Route::post('/', [CategoryController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [CategoryController::class, 'edit'])->name('edit');
            Route::put('/{id}', [CategoryController::class, 'update'])->name('update');
            Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('destroy');
        });
});

