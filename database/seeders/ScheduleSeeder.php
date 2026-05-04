<?php

namespace Database\Seeders;

use App\Models\BeautyCenter;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $centers = BeautyCenter::all();
        
        foreach ($centers as $center) {
            // Monday to Friday
            for ($i = 1; $i <= 5; $i++) {
                $center->schedules()->create([
                    'day_of_week' => $i,
                    'opening_time' => '09:00:00',
                    'closing_time' => '18:00:00',
                    'is_closed' => false
                ]);
            }

            // Saturday
            $center->schedules()->create([
                'day_of_week' => 6,
                'opening_time' => '10:00:00',
                'closing_time' => '14:00:00',
                'is_closed' => false
            ]);

            // Sunday
            $center->schedules()->create([
                'day_of_week' => 7,
                'opening_time' => '00:00:00',
                'closing_time' => '00:00:00',
                'is_closed' => true
            ]);
        }
    }
}
