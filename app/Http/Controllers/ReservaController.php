<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationConfirmed;

class ReservaController extends Controller
{
    public function index()
    {
        $reservations = auth()->user()->reservations()->with(['service', 'reservable'])->latest()->get();
        
        return Inertia::render('Reservas/Index', [
            'reservations' => $reservations
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'local_id' => 'required|integer',
            'category' => 'required|string',
            'service_id' => 'required|exists:services,id',
            'reservation_date' => 'required|date|after_or_equal:today',
            'reservation_time' => 'required|string',
            'guests' => 'nullable|integer|min:1',
            'special_request' => 'nullable|string',
        ]);

        $categoryMap = [
            'gastronomia' => \App\Models\Restaurant::class,
            'deportes' => \App\Models\SportCenter::class,
            'ocio' => \App\Models\LeisureCenter::class,
            'salud' => \App\Models\HealthCenter::class,
            'belleza' => \App\Models\BeautyCenter::class,
        ];

        if (!isset($categoryMap[$validated['category']])) {
            return back()->withErrors(['category' => 'Categoría no válida.']);
        }

        $reservableType = $categoryMap[$validated['category']];

        $reservation = Reservation::create([
            'user_id' => auth()->id(),
            'reservable_id' => $validated['local_id'],
            'reservable_type' => $reservableType,
            'service_id' => $validated['service_id'],
            'reservation_date' => $validated['reservation_date'],
            'reservation_time' => $validated['reservation_time'],
            'guests' => $validated['guests'] ?? 1,
            'special_request' => $validated['special_request'] ?? null,
            'status' => 'confirmed',
        ]);

        // Enviar email de confirmación
        Mail::to(auth()->user()->email)->send(new ReservationConfirmed($reservation));

        return back()->with('success', 'Reserva realizada correctamente.');
    }

    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);

        if ($reservation->user_id !== auth()->id()) {
            abort(403);
        }

        $reservation->delete();

        return back()->with('success', 'Reserva cancelada correctamente.');
    }
}
