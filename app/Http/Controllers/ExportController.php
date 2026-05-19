<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Restaurant;
use App\Models\SportCenter;
use App\Models\HealthCenter;
use App\Models\BeautyCenter;
use App\Models\LeisureCenter;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{
    public function users()
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $users = User::with('roles')->get();
        $pdf = Pdf::loadView('exports.users', compact('users'));
        return $pdf->download('informe_usuarios_' . date('Y_m_d') . '.pdf');
    }

    public function locals()
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $restaurants = Restaurant::all();
        $sportCenters = SportCenter::all();
        $healthCenters = HealthCenter::all();
        $beautyCenters = BeautyCenter::all();
        $leisureCenters = LeisureCenter::all();

        $locals = collect()
            ->merge($restaurants)
            ->merge($sportCenters)
            ->merge($healthCenters)
            ->merge($beautyCenters)
            ->merge($leisureCenters)
            ->sortByDesc('created_at');

        $pdf = Pdf::loadView('exports.locals', compact('locals'));
        return $pdf->download('informe_locales_' . date('Y_m_d') . '.pdf');
    }

    public function reservations()
    {
        $user = auth()->user();
        if (!$user->hasRole(['admin', 'empresa'])) {
            abort(403, 'Unauthorized action.');
        }

        $isAdmin = $user->hasRole('admin');
        $allReservations = \App\Models\Reservation::with(['user', 'reservable', 'service'])->latest()->get();

        if (!$isAdmin) {
            $reservations = $allReservations->filter(function ($reservation) use ($user) {
                return $reservation->reservable && $reservation->reservable->user_id === $user->id;
            })->values();
        } else {
            $reservations = $allReservations;
        }

        $pdf = Pdf::loadView('exports.reservations', compact('reservations'));
        return $pdf->download('informe_reservas_' . date('Y_m_d') . '.pdf');
    }
}
