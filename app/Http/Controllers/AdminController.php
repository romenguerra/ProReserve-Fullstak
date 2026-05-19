<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Reservation;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationConfirmed;

class AdminController extends Controller
{
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|string|in:admin,cliente,empresa',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
        ]);

        $user->assignRole($request->role);

        return back()->with('success', 'Usuario creado exitosamente.');
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|string|in:admin,cliente,empresa',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->password) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);
        $user->syncRoles([$request->role]);

        return back()->with('success', 'Usuario actualizado exitosamente.');
    }

    public function destroyUser(User $user)
    {
        // Evitar que el admin se borre a sí mismo
        if (auth()->id() === $user->id) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta de administrador.');
        }

        $user->delete();

        return back()->with('success', 'Usuario eliminado correctamente.');
    }

    public function updateLocal(Request $request, $id, $type)
    {
        $modelClass = $this->getModelClass($type);
        $local = $modelClass::findOrFail($id);

        if (!auth()->user()->hasRole('admin') && $local->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para actualizar este local.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string',
            'local_type' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
            'smoking_area' => 'boolean',
            'has_parking' => 'boolean',
            'has_emergency' => 'boolean',
        ]);

        $data = $request->except(['image', 'schedules']);

        // Gestión de la Imagen
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('locales', 'public');
            $data['image'] = '/storage/' . $path;
        }

        $local->update($data);

        // 1. Sincronización de Horarios
        if ($request->has('schedules')) {
            $schedules = is_string($request->schedules) ? json_decode($request->schedules, true) : $request->schedules;
            foreach ($schedules as $sched) {
                $local->schedules()->updateOrCreate(
                    ['day_of_week' => $sched['day_of_week']],
                    [
                        'is_closed' => filter_var($sched['is_closed'], FILTER_VALIDATE_BOOLEAN),
                        'opening_time' => $sched['opening_time'],
                        'closing_time' => $sched['closing_time'],
                    ]
                );
            }
        }

        // 2. Sincronización de Recursos (Zonas)
        if ($request->has('resources')) {
            $resources = is_string($request->resources) ? json_decode($request->resources, true) : $request->resources;
            $local->resources()->delete(); // Limpiamos y recreamos para simplificar
            foreach ($resources as $res) {
                $isExclusive = ($res['resource_type'] ?? 'shared') === 'exclusive_unit';
                $local->resources()->create([
                    'name' => $res['name'],
                    'capacity' => $isExclusive ? (($res['unit_count'] ?? 1) * ($res['unit_capacity'] ?? 1)) : ($res['max_guests_per_booking'] ?? $res['capacity'] ?? 1),
                    'resource_type' => $res['resource_type'] ?? 'shared',
                    'unit_count' => $isExclusive ? ($res['unit_count'] ?? 1) : null,
                    'unit_capacity' => $isExclusive ? ($res['unit_capacity'] ?? 1) : null,
                    'max_guests_per_booking' => $res['max_guests_per_booking'] ?? $res['capacity'] ?? 1,
                ]);
            }
        }

        // 3. Sincronización de Servicios
        if ($request->has('services')) {
            $services = is_string($request->services) ? json_decode($request->services, true) : $request->services;
            $local->services()->delete(); // Limpiamos y recreamos para simplificar
            foreach ($services as $srv) {
                $local->services()->create([
                    'name' => $srv['name'],
                    'duration_minutes' => $srv['duration_minutes'],
                    'price' => $srv['price']
                ]);
            }
        }

        return back()->with('success', 'Establecimiento actualizado con éxito.');
    }

    public function destroyLocal($id, $type)
    {
        $modelClass = $this->getModelClass($type);
        $local = $modelClass::findOrFail($id);

        if (!auth()->user()->hasRole('admin') && $local->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para eliminar este local.');
        }

        $local->delete();

        return back()->with('success', 'Local eliminado correctamente.');
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $isAdmin = $user->hasRole('admin');

        $queryFn = function ($model) use ($isAdmin, $user) {
            $query = $model::with(['schedules', 'resources', 'services']);
            if (!$isAdmin) {
                $query->where('user_id', $user->id);
            }
            return $query->get();
        };

        // For reservations, if admin, get all. If empresa, get reservations for their locals.
        $reservationsQuery = \App\Models\Reservation::with(['user', 'reservable', 'service'])->latest();
        if (!$isAdmin) {
            // Find all local IDs for the user across all types
            // This is a bit complex, so an easier way is to filter the collection after getting it
            // Or just check reservable->user_id in PHP if we eager load it.
        }

        $restaurants = $queryFn(\App\Models\Restaurant::class);
        $sportCenters = $queryFn(\App\Models\SportCenter::class);
        $healthCenters = $queryFn(\App\Models\HealthCenter::class);
        $beautyCenters = $queryFn(\App\Models\BeautyCenter::class);
        $leisureCenters = $queryFn(\App\Models\LeisureCenter::class);

        // Filter reservations
        $allReservations = \App\Models\Reservation::with(['user', 'reservable', 'service', 'resource'])->latest()->get();
        if (!$isAdmin) {
            $reservations = $allReservations->filter(function ($reservation) use ($user) {
                return $reservation->reservable && $reservation->reservable->user_id === $user->id;
            })->values();
        } else {
            $reservations = $allReservations;
        }

        return Inertia::render('Admin/Dashboard', [
            'users' => $isAdmin ? User::with('roles')->get() : [],
            'restaurants' => $restaurants,
            'sportCenters' => $sportCenters,
            'healthCenters' => $healthCenters,
            'beautyCenters' => $beautyCenters,
            'leisureCenters' => $leisureCenters,
            'reservations' => $reservations
        ]);
    }

    public function usuarios(Request $request)
    {
        $usuarios = User::with('roles')->get();
        return Inertia::render('Admin/Usuarios', [
            'usuarios' => $usuarios
        ]);
    }

    public function approveLocal($id, $type)
    {
        $modelClass = $this->getModelClass($type);
        $local = $modelClass::findOrFail($id);

        // 1. Activar el local
        $local->update(['status' => 'active']);

        // 2. Cambiar rol al usuario dueño (si existe)
        if ($local->user_id) {
            $user = User::find($local->user_id);
            if ($user && !$user->hasRole('admin')) {
                $user->syncRoles(['empresa']);
            }
        }

        return back()->with('success', 'Establecimiento aprobado. El usuario ahora tiene el rol de Empresa.');
    }

    public function rejectLocal($id, $type)
    {
        $modelClass = $this->getModelClass($type);
        $local = $modelClass::findOrFail($id);

        $local->update(['status' => 'rejected']);

        return back()->with('warning', 'La solicitud ha sido rechazada.');
    }

    public function updateReservation(Request $request, Reservation $reservation)
    {
        // Seguridad: Si es empresa, verificar que el local le pertenece
        if (!auth()->user()->hasRole('admin')) {
            $local = $reservation->reservable;
            if ($local->user_id !== auth()->id()) {
                abort(403, 'No tienes permiso para gestionar esta reserva.');
            }
        }

        $request->validate([
            'reservation_date' => 'required|date',
            'reservation_time' => 'required|string',
            'status' => 'required|string|in:pending,confirmed,cancelled,completed',
            'guests' => 'required|integer|min:1',
            'special_request' => 'nullable|string',
        ]);

        $oldStatus = $reservation->status;

        $reservation->update($request->only([
            'reservation_date',
            'reservation_time',
            'status',
            'guests',
            'special_request'
        ]));

        // Si cambia el estado a confirmado, enviar email automático al cliente o correo manual
        if ($oldStatus !== 'confirmed' && $reservation->status === 'confirmed') {
            $email = $reservation->user ? $reservation->user->email : $reservation->customer_email;
            if ($email) {
                try {
                    Mail::to($email)->send(new ReservationConfirmed($reservation));
                } catch (\Exception $e) {
                    \Log::error('Error enviando email de confirmación de reserva: ' . $e->getMessage());
                }
            }
        }

        return back()->with('success', 'Reserva actualizada correctamente.');
    }

    public function destroyReservation(Reservation $reservation)
    {
        // Seguridad: Si es empresa, verificar propiedad
        if (!auth()->user()->hasRole('admin')) {
            if ($reservation->reservable->user_id !== auth()->id()) {
                abort(403, 'No tienes permiso para eliminar esta reserva.');
            }
        }

        $reservation->delete();
        return back()->with('success', 'Reserva eliminada correctamente.');
    }

    public function storeManualReservation(Request $request)
    {
        $request->validate([
            'reservable_id' => 'required|integer',
            'reservable_type' => 'required|string|in:restaurant,sport_center,health_center,beauty_center,leisure_center',
            'service_id' => 'nullable|integer',
            'resource_id' => 'nullable|integer',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'nullable|string|size:9|regex:/^[0-9]+$/',
            'reservation_date' => 'required|date',
            'reservation_time' => 'required|string',
            'guests' => 'required|integer|min:1',
            'special_request' => 'nullable|string',
            'status' => 'required|string|in:pending,confirmed,cancelled,completed',
        ]);

        $modelClass = $this->getModelClass($request->reservable_type);

        // Seguridad: Si es empresa, verificar propiedad del local
        if (!auth()->user()->hasRole('admin')) {
            $local = $modelClass::findOrFail($request->reservable_id);
            if ($local->user_id !== auth()->id()) {
                abort(403, 'No tienes permiso para crear reservas en este local.');
            }
        }

        Reservation::create([
            'user_id' => null, // Cita manual
            'reservable_type' => $modelClass,
            'reservable_id' => $request->reservable_id,
            'service_id' => $request->service_id,
            'resource_id' => $request->resource_id,
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'reservation_date' => $request->reservation_date,
            'reservation_time' => $request->reservation_time,
            'guests' => $request->guests,
            'special_request' => $request->special_request,
            'status' => $request->status,
        ]);

        return back()->with('success', 'Reserva manual creada correctamente.');
    }

    private function getModelClass($type)
    {
        return match ($type) {
            'restaurant' => \App\Models\Restaurant::class,
            'sport_center' => \App\Models\SportCenter::class,
            'health_center' => \App\Models\HealthCenter::class,
            'beauty_center' => \App\Models\BeautyCenter::class,
            'leisure_center' => \App\Models\LeisureCenter::class,
            default => \App\Models\Restaurant::class,
        };
    }
}
