<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\leveranciersController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Role-based dashboards
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        return match($user->role) {
            'Directie' => redirect()->route('dashboard.admin'),
            'Magazijnmedewerker' => redirect()->route('dashboard.worker'),
            'Vrijwilliger' => redirect()->route('dashboard.user'),
            default => redirect()->route('dashboard.user'),
        };
    })->name('dashboard');

    Route::get('/dashboard/admin', [DashboardController::class, 'admin'])
        ->middleware('role:Directie')
        ->name('dashboard.admin');

    // levering & leverancier routes begin

    Route::get('/admin/leveranciers', [leveranciersController::class, 'index'])
        ->middleware('role:Directie')
        ->name('leveranciers.index');

    Route::post('/admin/leveranciers/nieuwleverancier', [leveranciersController::class, 'storeLeverancier'])
        ->middleware('role:Directie')
        ->name('leveranciers.storeLeverancier');
    
    Route::post('/admin/leveranciers/nieuwlevering', [leveranciersController::class, 'storeLevering'])
        ->middleware('role:Directie')
        ->name('leveranciers.storeLevering');

    Route::delete('/admin/leveranciers/{id}/deleteleverancier', [leveranciersController::class, 'softDeleteLeverancier'])
        ->middleware('role:Directie')
        ->name('leveranciers.softDeleteleverancier');

        Route::delete('/admin/leveranciers/{id}/deletelevering', [leveranciersController::class, 'softDeleteLevering'])
        ->middleware('role:Directie')
        ->name('leveranciers.softDeletelevering');

    Route::get('/admin/leveranciers/{id}/editleverancier', [leveranciersController::class, 'editleverancier'])
        ->middleware('role:Directie')
        ->name('leveranciers.editleverancier');

    Route::put('/admin/leveranciers/{id}/updateleverancier', [leveranciersController::class, 'updateleverancier'])
        ->middleware('role:Directie')
        ->name('leveranciers.updateleverancier');

    Route::get('/admin/leveranciers/{id}/editlevering', [leveranciersController::class, 'editlevering'])
        ->middleware('role:Directie')
        ->name('leveranciers.editlevering');

    Route::put('/admin/leveranciers/{id}/updatelevering', [leveranciersController::class, 'updatelevering'])
        ->middleware('role:Directie')
        ->name('leveranciers.updatelevering');



    // levering & leverancier routes eind

    Route::get('/dashboard/worker', [DashboardController::class, 'worker'])
        ->middleware('role:Magazijnmedewerker')
        ->name('dashboard.worker');

    Route::get('/dashboard/user', [DashboardController::class, 'user'])
        ->middleware('role:Vrijwilliger')
        ->name('dashboard.user');
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

// Load modular route files (merge-friendly: teammates add files, no web.php edits)
foreach (glob(__DIR__.'/modules/*.php') as $routeFile) {
    require $routeFile;
}
