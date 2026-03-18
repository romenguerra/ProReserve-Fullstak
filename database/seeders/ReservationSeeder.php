<?php

namespace Database\Seeders;

use App\Models\Barbershop;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ReservationSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        $barbershop = Barbershop::first();

        if ($user && $barbershop && $barbershop->services()->count() > 0) {
            $service = $barbershop->services()->first();

            $barbershop->reservations()->create([
                'user_id' => $user->id,
                'service_id' => $service->id,
                'reservation_date' => Carbon::now()->addDays(2)->toDateString(),
                'reservation_time' => '10:00:00',
                'status' => 'confirmed',
                'special_request' => 'Por favor, llamar al llegar.'
            ]);
        }
    }
}
