<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
})->name('home');

// Página de Servicios (pública)
Route::get('/servicios', function () {
    return Inertia::render('Servicios', [
        // Aquí podrías pasar datos desde la base de datos:
        // 'servicios' => Servicio::all(),
    ]);
})->name('servicios');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard-Example');
})->middleware(['auth', 'verified', 'role:admin'])->name('dashboard');

// Ruta para visualizar perfil (diferente de editar)
Route::get('/perfil', function () {
    return Inertia::render('Profile/Show', [
        'user' => auth()->user(),
    ]);
})->middleware(['auth'])->name('profile.show');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/interests/setup', [App\Http\Controllers\InterestController::class, 'create'])->name('interests.create');
    Route::post('/interests', [App\Http\Controllers\InterestController::class, 'store'])->name('interests.store');
});

// Rutas protegidas por rol - EJEMPLOS
// Descomenta cuando crees los controladores correspondientes

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/usuarios', [AdminController::class, 'usuarios'])->name('admin.usuarios');
    Route::get('/admin/servicios', [AdminController::class, 'servicios'])->name('admin.servicios');
    });

// Route::middleware(['auth', 'role:admin|cliente'])->group(function () {
//     Route::resource('reservas', App\Http\Controllers\ReservaController::class);
// });

require __DIR__ . '/auth.php';
