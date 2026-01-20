<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\VoedselpakketController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Role-based dashboards
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        return match($user->role) {
            'Directie' => redirect()->route('dashboard.admin'),
            'Magazijnmedewerker' => redirect()->route('dashboard.worker'),
            'Vrijwilliger' => redirect()->route('dashboard.user'),
        };
    })->name('dashboard');

    Route::get('/dashboard/admin', [DashboardController::class, 'admin'])
        ->middleware('role:Directie')
        ->name('dashboard.admin');

    Route::get('/dashboard/worker', [DashboardController::class, 'worker'])
        ->middleware('role:Magazijnmedewerker')
        ->name('dashboard.worker');

    Route::get('/dashboard/user', [DashboardController::class, 'user'])
        ->middleware('role:Vrijwilliger')
        ->name('dashboard.user');

    Route::get('/voedselpakketten', [VoedselpakketController::class, 'index'])
        ->middleware('role:Vrijwilliger')
        ->name('voedselpakketten.index');

    Route::get('/voedselpakketten/create', [VoedselpakketController::class, 'create'])
        ->middleware('role:Vrijwilliger')
        ->name('voedselpakketten.create');

    Route::get('/voedselpakketten/{voedselpakketid}', [VoedselpakketController::class, 'show'])
        ->middleware('role:Vrijwilliger')
        ->name('voedselpakketten.show');

    Route::post('/voedselpakketten', [VoedselpakketController::class, 'store'])
        ->middleware('role:Vrijwilliger')
        ->name('voedselpakketten.store');

    Route::delete('/voedselpakketten/{voedselpakketid}', [VoedselpakketController::class, 'destroy'])
        ->middleware('role:Vrijwilliger')
        ->name('voedselpakketten.destroy');

    Route::post('/voedselpakketten/{voedselpakketid}/deliver', [VoedselpakketController::class, 'deliver'])
        ->middleware('role:Vrijwilliger')
        ->name('voedselpakketten.deliver');

    Route::get('/voedselpakketten/{voedselpakketid}/edit', [VoedselpakketController::class, 'edit'])
        ->middleware('role:Vrijwilliger')
        ->name('voedselpakketten.edit');

    Route::post('/voedselpakketten/{voedselpakketid}/update', [VoedselpakketController::class, 'update'])
        ->middleware('role:Vrijwilliger')
        ->name('voedselpakketten.update');

    Route::get('/voedselpakketten/producten/{id}', [VoedselpakketController::class, 'getproducten'])
        ->name('voedselpakketten.producten');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes
Route::middleware(['auth', 'role:Directie'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class);
});

require __DIR__.'/auth.php';
