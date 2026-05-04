<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Services\ScheduleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\Reservation;
use Illuminate\Database\Eloquent\Relations\Relation;

class ReservationController extends Controller
{
    private ScheduleService $scheduleService;

    public function __construct(ScheduleService $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }

    /**
     * Get available slots for a given center, date, and service.
     */
    public function getAvailableSlots(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string', // e.g. BeautyCenter
            'id' => 'required|integer',
            'date' => 'required|date|after_or_equal:today',
            'service_id' => 'required|exists:services,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $typeClass = "App\\Models\\" . $request->type;
        if (!class_exists($typeClass)) {
            return response()->json(['error' => 'Invalid place type'], 400);
        }

        $place = $typeClass::find($request->id);
        if (!$place) {
            return response()->json(['error' => 'Place not found'], 404);
        }

        $service = Service::find($request->service_id);
        $duration = $service ? $service->duration_minutes : 30;

        $slots = $this->scheduleService->getAvailableSlots($place, $request->date, $duration);

        return response()->json([
            'date' => $request->date,
            'available_slots' => $slots
        ]);
    }

    /**
     * Store a new reservation
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'id' => 'required|integer',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'service_id' => 'required|exists:services,id',
            'guests' => 'nullable|integer|min:1',
            'special_request' => 'nullable|string'
        ]);

        $typeClass = "App\\Models\\" . $request->type;
        $place = $typeClass::findOrFail($request->id);
        $service = Service::findOrFail($request->service_id);

        // Double check availability to prevent race conditions
        $slots = $this->scheduleService->getAvailableSlots($place, $request->date, $service->duration_minutes);
        
        $slotValid = false;
        // In the format "H:i", make sure times match
        $reqTime = Carbon::createFromFormat('H:i', $request->time)->format('H:i');
        foreach($slots as $s) {
            if (Carbon::createFromFormat('H:i', $s)->format('H:i') === $reqTime) {
                $slotValid = true;
                break;
            }
        }

        if (!$slotValid) {
            return response()->json(['message' => 'The selected time slot is no longer available.'], 422);
        }

        // Create reservation
        $reservation = Reservation::create([
            'user_id' => auth()->id(),
            'reservable_type' => $typeClass,
            'reservable_id' => $place->id,
            'service_id' => $service->id,
            'reservation_date' => $request->date,
            'reservation_time' => $request->time,
            'guests' => $request->guests ?? 1,
            'status' => 'pending',
            'special_request' => $request->special_request
        ]);

        return response()->json(['message' => 'Reservation created successfully!', 'reservation' => $reservation], 201);
    }
}
