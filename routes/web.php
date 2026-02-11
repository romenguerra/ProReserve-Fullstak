<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rutas protegidas por rol - EJEMPLOS
// Descomenta cuando crees los controladores correspondientes

// Route::middleware(['auth', 'role:admin'])->group(function () {
//     Route::get('/admin/dashboard', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.dashboard');
//     Route::get('/admin/usuarios', [App\Http\Controllers\AdminController::class, 'usuarios'])->name('admin.usuarios');
//     Route::get('/admin/servicios', [App\Http\Controllers\AdminController::class, 'servicios'])->name('admin.servicios');
// });

// Route::middleware(['auth', 'role:admin|cliente'])->group(function () {
//     Route::resource('reservas', App\Http\Controllers\ReservaController::class);
// });

require __DIR__.'/auth.php';
