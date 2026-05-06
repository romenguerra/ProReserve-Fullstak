<?php

namespace Database\Seeders;

use App\Models\BeautyCenter;
use App\Models\Restaurant;
use Illuminate\Database\Seeder;

class ResourceSeeder extends Seeder
{
    public function run(): void
    {
        $restaurants = Restaurant::all();
        foreach ($restaurants as $restaurant) {
            if ($restaurant->resources()->count() == 0) {
                $restaurant->resources()->create(['name' => 'Terraza', 'capacity' => 10]);
                $restaurant->resources()->create(['name' => 'Salón Interior', 'capacity' => 20]);
            }
        }

        $beautyCenters = BeautyCenter::all();
        foreach ($beautyCenters as $center) {
            if ($center->resources()->count() == 0) {
                $center->resources()->create(['name' => 'Peluquera Ana', 'capacity' => 1]);
                $center->resources()->create(['name' => 'Peluquero Juan', 'capacity' => 1]);
            }
        }
    }
}
