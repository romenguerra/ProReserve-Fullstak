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
    public function getAvailableSlots(Model $reservable, string $date, int $durationMinutes)
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

        while ($currentTime->copy()->addMinutes($durationMinutes)->lte($closeTime)) {
            $slots[] = $currentTime->format('H:i');
            $currentTime->addMinutes($durationMinutes); // Or a fixed interval like 30
        }

        // 3. Get existing reservations for the day
        $reservations = $reservable->reservations()
            ->where('reservation_date', $requestedDate->format('Y-m-d'))
            ->whereIn('status', ['pending', 'confirmed'])
            ->get();

        // 4. Filter occupied slots
        $availableSlots = array_values(array_filter($slots, function ($slotTime) use ($reservations, $durationMinutes, $date) {
            $slotStart = Carbon::parse($date . ' ' . $slotTime);
            $slotEnd = $slotStart->copy()->addMinutes($durationMinutes);

            foreach ($reservations as $reservation) {
                $resDuration = $reservation->service ? $reservation->service->duration_minutes : $durationMinutes; // fallback
                $resStart = Carbon::parse($date . ' ' . $reservation->reservation_time);
                $resEnd = $resStart->copy()->addMinutes($resDuration);

                // Check for overlap
                // Any overlap means the slot is invalid
                if ($slotStart->lt($resEnd) && $slotEnd->gt($resStart)) {
                    return false; // Occupied
                }
            }

            // Also check if slot is in the past (if today)
            if ($slotStart->isPast()) {
                return false;
            }

            return true;
        }));

        return $availableSlots;
    }
}
