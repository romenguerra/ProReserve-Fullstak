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

Route::get('/contacto', function () {
    return Inertia::render('Contacto');
})->name('contacto');

Route::get('/gastronomia', function () {
    return Inertia::render('Gastronomia', [
        'restaurants' => \App\Models\Restaurant::with(['services', 'resources'])->where('status', 'active')->get(),
    ]);
})->name('gastronomia');

Route::get('/deportes', function () {
    return Inertia::render('Deportes', [
        'sportCenters' => \App\Models\SportCenter::with(['services', 'resources'])->where('status', 'active')->get(),
    ]);
})->name('deportes');

Route::get('/ocio', function () {
    return Inertia::render('Ocio', [
        'leisureCenters' => \App\Models\LeisureCenter::with(['services', 'resources'])->where('status', 'active')->get(),
    ]);
})->name('ocio');

Route::get('/salud', function () {
    return Inertia::render('Salud', [
        'healthCenters' => \App\Models\HealthCenter::with(['services', 'resources'])->where('status', 'active')->get(),
    ]);
})->name('salud');

Route::get('/belleza', function () {
    return Inertia::render('Belleza', [
        'beautyCenters' => \App\Models\BeautyCenter::with(['services', 'resources'])->where('status', 'active')->get(),
    ]);
})->name('belleza');


Route::get('/dashboard', function () {
    return Inertia::render('Admin/Dashboard', [
        'users' => \App\Models\User::with('roles')->get(),
        'restaurants' => \App\Models\Restaurant::all(),
        'sportCenters' => \App\Models\SportCenter::all(),
        'healthCenters' => \App\Models\HealthCenter::all(),
        'beautyCenters' => \App\Models\BeautyCenter::all(),
        'leisureCenters' => \App\Models\LeisureCenter::all(),
        'reservations' => \App\Models\Reservation::with(['user', 'reservable', 'service'])->latest()->get(),
    ]);
})->middleware(['auth', 'verified', 'role:admin'])->name('dashboard');



// Ruta para visualizar perfil (diferente de editar)
Route::get('/perfil', function () {
    return Inertia::render('Profile/Show', [
        'user' => auth()->user()->load("interests"),
    ]);
})->middleware(['auth'])->name('profile.show');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/interests/setup', [App\Http\Controllers\InterestController::class, 'create'])->name('interests.create');
    Route::post('/interests', [App\Http\Controllers\InterestController::class, 'store'])->name('interests.store');

    Route::resource('reservas', App\Http\Controllers\ReservaController::class)->only(['index', 'store', 'destroy']);

    Route::get('/locales/crear', [\App\Http\Controllers\LocalController::class, 'create'])->name('locales.create');
    Route::post('/locales', [\App\Http\Controllers\LocalController::class, 'store'])->name('locales.store');
});

// Rutas protegidas por rol - EJEMPLOS
// Descomenta cuando crees los controladores correspondientes

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/usuarios', [AdminController::class, 'usuarios'])->name('admin.usuarios');
    Route::get('/admin/servicios', [AdminController::class, 'servicios'])->name('admin.servicios');
    Route::post('/admin/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::put('/admin/users/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
    Route::post('/admin/locales/{id}/{type}/approve', [AdminController::class, 'approveLocal'])->name('admin.locales.approve');
    Route::post('/admin/locales/{id}/{type}/reject', [AdminController::class, 'rejectLocal'])->name('admin.locales.reject');
    Route::put('/admin/locales/{id}/{type}', [AdminController::class, 'updateLocal'])->name('admin.locales.update');
    Route::delete('/admin/locales/{id}/{type}', [AdminController::class, 'destroyLocal'])->name('admin.locales.destroy');
    Route::put('/admin/reservations/{reservation}', [AdminController::class, 'updateReservation'])->name('admin.reservations.update');
    Route::delete('/admin/reservations/{reservation}', [AdminController::class, 'destroyReservation'])->name('admin.reservations.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/reservations', [App\Http\Controllers\ReservationController::class, 'store'])->name('reservations.store');
});

Route::get('/api/availability', [App\Http\Controllers\ReservationController::class, 'getAvailableSlots'])->name('availability');
require __DIR__ . '/auth.php';