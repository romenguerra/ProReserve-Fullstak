<?php

namespace Database\Seeders;

use App\Models\Interest;
use App\Models\LeisureCenter;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LeisureCenterSeeder extends Seeder
{
    public function run(): void
    {
        $ocioInterest = Interest::where('slug', 'ocio')->first();
        $cineInterest = Interest::where('slug', 'cine')->first();

        if (!$ocioInterest) {
            return;
        }

        $leisureCenters = [
            [
                'name' => 'Cines Splendid',
                'type' => 'cine',
                'description' => 'Disfruta de los últimos estrenos con la mejor tecnología de imagen y sonido.',
                'image' => 'https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?auto=format&fit=crop&q=80&w=800',
                'address' => 'Avenida del Cine, 5',
                'city' => 'Madrid',
                'phone' => '911223344',
                'opening_time' => '16:00',
                'closing_time' => '00:00',
                'interest_id' => $cineInterest ? $cineInterest->id : $ocioInterest->id,
            ],
            [
                'name' => 'The Blue Pub',
                'type' => 'bar',
                'description' => 'El mejor ambiente nocturno con una amplia selección de cervezas artesanales.',
                'image' => 'https://images.unsplash.com/photo-1514933651103-005eec06c04b?auto=format&fit=crop&q=80&w=800',
                'address' => 'Calle de la Luna, 12',
                'city' => 'Madrid',
                'phone' => '911223345',
                'opening_time' => '18:00',
                'closing_time' => '02:30',
                'interest_id' => $ocioInterest->id,
            ],
            [
                'name' => 'Bolera Pin Strike',
                'type' => 'bolera',
                'description' => 'Diversión para toda la familia con 20 pistas automáticas y zona de arcade.',
                'image' => 'https://images.unsplash.com/photo-1511512578047-dfb367046420?auto=format&fit=crop&q=80&w=800',
                'address' => 'Centro Comercial Gran Plaza, L20',
                'city' => 'Barcelona',
                'phone' => '931223346',
                'opening_time' => '11:00',
                'closing_time' => '23:00',
                'interest_id' => $ocioInterest->id,
            ],
            [
                'name' => 'Discoteca Neon Nights',
                'type' => 'discoteca',
                'description' => 'La sala más exclusiva de la ciudad con los mejores DJs internacionales.',
                'image' => 'https://images.unsplash.com/photo-1566737236500-c8ac43014a67?auto=format&fit=crop&q=80&w=800',
                'address' => 'Calle Principal, 100',
                'city' => 'Valencia',
                'phone' => '961223347',
                'opening_time' => '23:30',
                'closing_time' => '06:00',
                'interest_id' => $ocioInterest->id,
            ],
            [
                'name' => 'Bar de Copas El Refugio',
                'type' => 'bar',
                'description' => 'Cócteles de autor en un ambiente relajado con música jazz en directo.',
                'image' => 'https://images.unsplash.com/photo-1536935338788-846bb9981813?auto=format&fit=crop&q=80&w=800',
                'address' => 'Calle del Jazz, 3',
                'city' => 'Sevilla',
                'phone' => '954223348',
                'opening_time' => '19:00',
                'closing_time' => '03:00',
                'interest_id' => $ocioInterest->id,
            ]
        ];

        foreach ($leisureCenters as $data) {
            $data['slug'] = Str::slug($data['name']);
            LeisureCenter::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }
    }
}
