<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class LocalController extends Controller
{
    public function create()
    {
        return Inertia::render('CreateLocal');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:restaurant,sport_center,health_center,beauty_center,leisure_center',
            'name' => 'required|string|max:255',
            'local_type' => 'nullable|string',
            'description' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,avif|max:2048',
            
            'smoking_area' => 'boolean',
            'has_parking' => 'boolean',
            'has_emergency' => 'boolean',
            
            'address' => 'required|string',
            'city' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',

            // Array de horarios
            'schedules' => 'required|array|size:7',
            'schedules.*.day_of_week' => 'required|integer|between:1,7',
            'schedules.*.is_closed' => 'required|boolean',
            'schedules.*.opening_time' => 'nullable|string',
            'schedules.*.closing_time' => 'nullable|string',

            // Array de recursos
            'resources' => 'required|array|min:1',
            'resources.*.name' => 'required|string',
            'resources.*.capacity' => 'required|integer|min:1',
            
            // Array de servicios
            'services' => 'required|array|min:1',
            'services.*.name' => 'required|string',
            'services.*.duration_minutes' => 'required|integer',
            'services.*.price' => 'nullable|numeric',
        ]);

        $modelClass = match($validated['type']) {
            'restaurant' => \App\Models\Restaurant::class,
            'sport_center' => \App\Models\SportCenter::class,
            'health_center' => \App\Models\HealthCenter::class,
            'beauty_center' => \App\Models\BeautyCenter::class,
            'leisure_center' => \App\Models\LeisureCenter::class,
        };

        // Unguard para que no nos de error de asignación masiva
        Model::unguard();

        $data = [
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name'] . '-' . time()),
            'type' => $validated['local_type'],
            'description' => $validated['description'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'website' => $validated['website'],
            
            // Como las columnas de opening_time y closing_time existen en las tablas base,
            // podemos poner nulo o el horario del primer dia abierto.
            'opening_time' => null,
            'closing_time' => null,
            
            'address' => $validated['address'],
            'city' => $validated['city'] ?? 'Desconocida',
            'postal_code' => $validated['postal_code'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'interest_id' => 1,
            'status' => 'pending',
            'user_id' => auth()->id()
        ];

        // Manejo de la imagen
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('locales', 'public');
            $data['image'] = '/storage/' . $path;
        }

        // Añadimos datos específicos si la migración lo permite (para evitar errores SQL)
        if ($validated['type'] === 'restaurant') {
            $data['smoking_area'] = $validated['smoking_area'] ?? false;
        }

        if (in_array($validated['type'], ['sport_center', 'leisure_center', 'health_center'])) {
            $data['has_parking'] = $validated['has_parking'] ?? false;
        }

        if ($validated['type'] === 'health_center') {
            $data['has_emergency'] = $validated['has_emergency'] ?? false;
        }

        // Crear el local
        $local = $modelClass::create($data);

        // Guardar horarios (schedules)
        foreach ($validated['schedules'] as $schedule) {
            $local->schedules()->create([
                'day_of_week' => $schedule['day_of_week'],
                'is_closed' => $schedule['is_closed'],
                'opening_time' => $schedule['is_closed'] ? '00:00' : $schedule['opening_time'],
                'closing_time' => $schedule['is_closed'] ? '00:00' : $schedule['closing_time'],
            ]);
        }

        // Guardar múltiples recursos
        foreach ($validated['resources'] as $res) {
            $local->resources()->create([
                'name' => $res['name'],
                'capacity' => $res['capacity']
            ]);
        }

        // Crear los servicios base
        foreach ($validated['services'] as $srv) {
            $local->services()->create([
                'name' => $srv['name'],
                'duration_minutes' => $srv['duration_minutes'],
                'price' => $srv['price'] ?? 0,
            ]);
        }

        Model::reguard();

        return redirect()->route('home')->with('success', 'Establecimiento registrado con éxito.');
    }
}
