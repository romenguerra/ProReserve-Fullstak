<?php

namespace App\Services;

use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ScheduleService
{
    /**
     * Get available time slots for a business, for a specific date and service.
     */
    public function getAvailableSlots(Model $reservable, string $date, int $durationMinutes, int $requestedGuests = 1, ?int $resourceId = null)
    {
        $requestedDate = Carbon::parse($date);
        $dayOfWeek = $requestedDate->dayOfWeekIso; // 1 (Mon) - 7 (Sun)

        // 1. Get schedule for that day
        $schedule = $reservable->schedules()->where('day_of_week', $dayOfWeek)->first();

        if (!$schedule || $schedule->is_closed) {
            return []; // No schedule or closed that day
        }

        // 2. Generate all possible slots
        $openTime = Carbon::parse($date . ' ' . $schedule->opening_time);
        $closeTime = Carbon::parse($date . ' ' . $schedule->closing_time);

        $slots = [];
        $currentTime = $openTime->copy();
        $intervalMinutes = 30; // Frecuencia con la que aparecen las opciones de hora (ej: cada 30 min)

        while ($currentTime->copy()->addMinutes($durationMinutes)->lte($closeTime)) {
            $slots[] = $currentTime->format('H:i');
            $currentTime->addMinutes($intervalMinutes);
        }

        // 3. Get existing reservations for the day
        $reservations = $reservable->reservations()
            ->where('reservation_date', $requestedDate->format('Y-m-d'))
            ->when($resourceId, function($query, $resourceId) {
                return $query->where('resource_id', $resourceId);
            })
            ->whereIn('status', ['pending', 'confirmed'])
            ->get();

        if ($resourceId) {
            $resource = \App\Models\Resource::find($resourceId);
            $capacity = $resource ? $resource->capacity : ($schedule->capacity ?? 1);
        } else {
            $capacity = $schedule->capacity ?? 1;
        }

        // 4. Filter occupied slots
        $availableSlots = array_values(array_filter($slots, function ($slotTime) use ($reservations, $durationMinutes, $date, $capacity, $requestedGuests) {
            $slotStart = Carbon::parse($date . ' ' . $slotTime);
            $slotEnd = $slotStart->copy()->addMinutes($durationMinutes);

            $overlapCount = 0;

            foreach ($reservations as $reservation) {
                $resDuration = $reservation->service ? $reservation->service->duration_minutes : $durationMinutes; // fallback
                $resStart = Carbon::parse($date . ' ' . $reservation->reservation_time);
                $resEnd = $resStart->copy()->addMinutes($resDuration);

                // Check for overlap
                if ($slotStart->lt($resEnd) && $slotEnd->gt($resStart)) {
                    $overlapCount += $reservation->guests ?? 1;
                }
            }

            // Also check if slot is in the past (if today)
            if ($slotStart->isPast()) {
                return false;
            }

            // Return true only if we have enough capacity for the requested guests
            return ($overlapCount + $requestedGuests) <= $capacity;
        }));

        return $availableSlots;
    }
}
