<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Interest;
use Illuminate\Support\Str;

class InterestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $interests = [
            ['name' => 'Gastronomía', 'icon' => '🍽️'],
            ['name' => 'Deporte', 'icon' => '⚽'],
            ['name' => 'Salud', 'icon' => '🏥'],
            ['name' => 'Ocio', 'icon' => '🎡'],
            ['name' => 'Personal', 'icon' => '👤'],
            ['name' => 'Tecnología', 'icon' => '💻'],
            ['name' => 'Arte', 'icon' => '🎨'],
            ['name' => 'Viajes', 'icon' => '✈️'],
            ['name' => 'Videojuegos', 'icon' => '🎮'],
            ['name' => 'Cine', 'icon' => '🎬'],
        ];

        foreach ($interests as $interest) {
            Interest::firstOrCreate(
                ['name' => $interest['name']],
                [
                    'slug' => Str::slug($interest['name']),
                    'icon' => $interest['icon'],
                ]
            );
        }
    }
}
