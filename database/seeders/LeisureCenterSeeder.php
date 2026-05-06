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
                'image' => '/storage/locales/ocio/cines-splendid.avif',
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
                'image' => '/storage/locales/ocio/the-blue-pub.avif',
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
                'image' => '/storage/locales/ocio/bolera-pin-strike.avif',
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
                'image' => '/storage/locales/ocio/discoteca-neon-nights.avif',
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
                'image' => '/storage/locales/ocio/bar-copas-el-refugio.avif',
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
