<?php

namespace Database\Seeders;

use App\Models\Interest;
use App\Models\Restaurant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RestaurantSeeder extends Seeder
{
    public function run(): void
    {
        $interest = Interest::where('slug', 'gastronomia')->first();

        if (!$interest) {
            return;
        }

        $restaurants = [
            [
                'name' => 'La Parrilla del Abuelo',
                'description' => 'Las mejores carnes a la brasa.',
                'address' => 'Calle Mayor, 10',
                'city' => 'Madrid',
                'phone' => '911234567',
                'has_terrace' => true,
                'opening_time' => '13:00',
                'closing_time' => '23:30',
            ],
            [
                'name' => 'Sushi Master',
                'description' => 'Auténtico sushi japonés.',
                'address' => 'Gran Vía, 40',
                'city' => 'Madrid',
                'phone' => '911234568',
                'has_terrace' => false,
                'opening_time' => '12:00',
                'closing_time' => '22:30',
            ]
        ];

        foreach ($restaurants as $data) {
            $data['slug'] = Str::slug($data['name']);
            $data['interest_id'] = $interest->id;
            Restaurant::create($data);
        }
    }
}
