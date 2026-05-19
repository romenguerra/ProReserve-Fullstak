<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Redirigir la ruta raíz al idioma por defecto (español)
Route::get('/', function () {
    return redirect('/es');
});

// Agrupar todas las rutas web bajo el prefijo de idioma para SEO
Route::prefix('{locale}')
    ->where(['locale' => 'en|es'])
    ->group(function () {

        Route::get('/', function () {
            return Inertia::render('Welcome', [
                'canLogin' => Route::has('login'),
                'canRegister' => Route::has('register'),
            ]);
        })->name('home');

        Route::get('/search', [\App\Http\Controllers\SearchController::class, 'index'])->name('search');

        // Página de Servicios (pública)
        Route::get('/servicios', function () {
            return Inertia::render('Servicios');
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

        Route::get('/dashboard', [AdminController::class, 'index'])
            ->middleware(['auth', 'verified', 'role:admin|empresa'])
            ->name('dashboard');

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
            
            Route::post('/reservations', [App\Http\Controllers\ReservationController::class, 'store'])->name('reservations.store');
        });

        // Rutas protegidas por rol 
        Route::middleware(['auth', 'role:admin'])->group(function () {
            Route::get('/admin/usuarios', [AdminController::class, 'usuarios'])->name('admin.usuarios');
            Route::post('/admin/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
            Route::put('/admin/users/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
            Route::delete('/admin/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
            Route::post('/admin/locales/{id}/{type}/approve', [AdminController::class, 'approveLocal'])->name('admin.locales.approve');
            Route::post('/admin/locales/{id}/{type}/reject', [AdminController::class, 'rejectLocal'])->name('admin.locales.reject');
            
            // PDF Exports
            Route::get('/admin/export/users', [\App\Http\Controllers\ExportController::class, 'users'])->name('admin.export.users');
            Route::get('/admin/export/locals', [\App\Http\Controllers\ExportController::class, 'locals'])->name('admin.export.locals');
        });

        Route::middleware(['auth', 'role:admin|empresa'])->group(function () {
            Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
            Route::get('/admin/servicios', [AdminController::class, 'servicios'])->name('admin.servicios');
            Route::put('/admin/locales/{id}/{type}', [AdminController::class, 'updateLocal'])->name('admin.locales.update');
            Route::delete('/admin/locales/{id}/{type}', [AdminController::class, 'destroyLocal'])->name('admin.locales.destroy');
            Route::put('/admin/reservations/{reservation}', [AdminController::class, 'updateReservation'])->name('admin.reservations.update');
            Route::delete('/admin/reservations/{reservation}', [AdminController::class, 'destroyReservation'])->name('admin.reservations.destroy');
            Route::post('/admin/reservations/manual', [AdminController::class, 'storeManualReservation'])->name('admin.reservations.storeManual');
            
            // PDF Exports for Admin & Empresa
            Route::get('/admin/export/reservations', [\App\Http\Controllers\ExportController::class, 'reservations'])->name('admin.export.reservations');
        });

        Route::post('/contacto', [App\Http\Controllers\ContactController::class, 'store'])->name('contacto.store');

        require __DIR__ . '/auth.php';
    });

// API Routes outside locale prefix
Route::get('/api/availability', [App\Http\Controllers\ReservationController::class, 'getAvailableSlots'])->name('availability');