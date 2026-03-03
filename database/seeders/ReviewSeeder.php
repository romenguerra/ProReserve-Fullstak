<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        $restaurant = Restaurant::first();

        if ($user && $restaurant) {
            $restaurant->reviews()->create([
                'user_id' => $user->id,
                'rating' => 5,
                'comment' => '¡Excelente comida y servicio inmejorable!'
            ]);

            // Update restaurant rating stats
            $restaurant->update([
                'rating_average' => 5.0,
                'rating_count' => 1,
            ]);
        }
    }
}
