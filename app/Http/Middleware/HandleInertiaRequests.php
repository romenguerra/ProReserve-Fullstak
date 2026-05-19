<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user() ? [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                    'avatar' => $request->user()->avatar,
                    'roles' => $request->user()->getRoleNames(),
                    'permissions' => $request->user()->getAllPermissions()->pluck('name'),
                    'interests' => $request->user()->interests,
                ] : null,
                'notifications' => $request->user() ? (function() use ($request) {
                    $user = $request->user();
                    $notifications = [];

                    // ROL DE ADMIN
                    if ($user->hasRole('admin')) {
                        $restaurants = \App\Models\Restaurant::where('status', 'pending')->get();
                        $sportCenters = \App\Models\SportCenter::where('status', 'pending')->get();
                        $healthCenters = \App\Models\HealthCenter::where('status', 'pending')->get();
                        $beautyCenters = \App\Models\BeautyCenter::where('status', 'pending')->get();
                        $leisureCenters = \App\Models\LeisureCenter::where('status', 'pending')->get();

                        $pendingLocals = collect()
                            ->merge($restaurants)
                            ->merge($sportCenters)
                            ->merge($healthCenters)
                            ->merge($beautyCenters)
                            ->merge($leisureCenters)
                            ->sortByDesc('created_at')
                            ->take(5);

                        foreach ($pendingLocals as $local) {
                            $notifications[] = [
                                'id' => 'solicitud_' . $local->id . '_' . class_basename($local),
                                'type' => 'solicitud',
                                'title' => 'Nueva Solicitud de Local',
                                'message' => "El local '{$local->name}' ha solicitado ser dado de alta en la plataforma.",
                                'created_at' => $local->created_at ? $local->created_at->diffForHumans() : 'Reciente',
                            ];
                        }
                    }
                    // ROL DE EMPRESA
                    elseif ($user->hasRole('empresa')) {
                        $reservations = \App\Models\Reservation::with(['reservable', 'service', 'user'])
                            ->whereHasMorph('reservable', 
                                [\App\Models\Restaurant::class, \App\Models\SportCenter::class, \App\Models\HealthCenter::class, \App\Models\BeautyCenter::class, \App\Models\LeisureCenter::class],
                                function ($query) use ($user) {
                                    $query->where('user_id', $user->id);
                                }
                            )
                            ->latest()
                            ->take(10)
                            ->get();

                        foreach ($reservations as $reserva) {
                            $status = $reserva->status;
                            $localName = $reserva->reservable->name ?? 'Local';
                            $serviceName = $reserva->service->name ?? 'Servicio';
                            $date = $reserva->reservation_date;
                            $clientName = $reserva->user->name ?? $reserva->customer_name ?? 'Un cliente';

                            if ($status === 'pending') {
                                $notifications[] = [
                                    'id' => 'emp_res_p_' . $reserva->id,
                                    'type' => 'reservation_empresa',
                                    'title' => 'Nueva Reserva Recibida',
                                    'message' => "{$clientName} ha realizado una reserva para {$serviceName} en {$localName} el {$date}.",
                                    'created_at' => $reserva->created_at ? $reserva->created_at->diffForHumans() : 'Reciente',
                                ];
                            } elseif ($status === 'cancelled' || $status === 'cancelada') {
                                $notifications[] = [
                                    'id' => 'emp_res_c_' . $reserva->id,
                                    'type' => 'reservation_empresa',
                                    'title' => 'Reserva Cancelada',
                                    'message' => "La reserva de {$clientName} para {$serviceName} el {$date} ha sido cancelada.",
                                    'created_at' => $reserva->updated_at ? $reserva->updated_at->diffForHumans() : 'Reciente',
                                ];
                            }
                        }
                    }
                    // ROL DE USUARIO / CLIENTE
                    else {
                        $reservations = $user->reservations()
                            ->with(['reservable', 'service'])
                            ->latest()
                            ->take(5)
                            ->get();

                        foreach ($reservations as $reserva) {
                            $status = strtolower($reserva->status ?? 'pending');
                            $localName = $reserva->reservable->name ?? 'Local';
                            $serviceName = $reserva->service->name ?? 'Servicio';
                            $date = $reserva->reservation_date;
                            $time = substr($reserva->reservation_time ?? '', 0, 5);

                            if ($status === 'pending' || $status === 'pendiente') {
                                $title = 'Reserva Pendiente';
                                $message = "Tu reserva para {$serviceName} en {$localName} está pendiente de confirmación para el {$date} a las {$time}h.";
                            } elseif ($status === 'cancelled' || $status === 'cancelada') {
                                $title = 'Reserva Cancelada';
                                $message = "Tu reserva para {$serviceName} en {$localName} el {$date} ha sido cancelada.";
                            } else {
                                $title = 'Reserva Confirmada';
                                $message = "Tu reserva para {$serviceName} en {$localName} está confirmada para el {$date} a las {$time}h.";
                            }

                            $notifications[] = [
                                'id' => 'usr_res_' . $reserva->id . '_' . $status,
                                'type' => 'reservation',
                                'title' => $title,
                                'message' => $message,
                                'created_at' => $reserva->updated_at ? $reserva->updated_at->diffForHumans() : ($reserva->created_at ? $reserva->created_at->diffForHumans() : 'Reciente'),
                            ];
                        }
                    }

                    if (!$user->hasRole('admin')) {
                        $models = [\App\Models\Restaurant::class, \App\Models\SportCenter::class, \App\Models\HealthCenter::class, \App\Models\BeautyCenter::class, \App\Models\LeisureCenter::class];
                        $locales = collect();
                        foreach ($models as $model) {
                            $userLocals = $model::where('user_id', $user->id)
                                ->whereIn('status', ['active', 'rejected'])
                                ->latest('updated_at')
                                ->take(2)
                                ->get();
                            $locales = $locales->merge($userLocals);
                        }

                        $recentLocals = $locales->sortByDesc('updated_at')->take(3);

                        foreach ($recentLocals as $local) {
                            $status = strtolower($local->status);
                            if ($status === 'active') {
                                $title = 'Local Aprobado';
                                $message = "¡Felicidades! Tu local '{$local->name}' ha sido aprobado y ya es público en la plataforma.";
                            } else {
                                $title = 'Local Rechazado';
                                $message = "Tu solicitud para el local '{$local->name}' ha sido rechazada por un administrador.";
                            }

                            array_unshift($notifications, [
                                'id' => 'loc_status_' . $local->id . '_' . class_basename($local) . '_' . $status,
                                'type' => 'solicitud',
                                'title' => $title,
                                'message' => $message,
                                'created_at' => $local->updated_at ? $local->updated_at->diffForHumans() : 'Reciente',
                            ]);
                        }
                    }
                    if (empty($notifications)) {
                        $notifications[] = [
                            'id' => 'welcome',
                            'type' => 'info',
                            'title' => 'Sin novedades',
                            'message' => 'Estás al día. No tienes notificaciones nuevas en este momento.',
                            'created_at' => 'Ahora',
                        ];
                    }

                    return array_slice($notifications, 0, 6);
                })() : [],
            ],
            'locale' => app()->getLocale(),
        ];
    }
}