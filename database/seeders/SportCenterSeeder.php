<?php

namespace Database\Seeders;

use App\Models\Interest;
use App\Models\SportCenter;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SportCenterSeeder extends Seeder
{
    public function run(): void
    {
        $interest = Interest::where('slug', 'deporte')->first();

        if (!$interest) {
            return;
        }

        $sportCenters = [
            [
                'name' => 'Gimnasio Fit Life',
                'type' => 'gimnasio',
                'description' => 'Tu centro de entrenamiento con la última tecnología y entrenadores expertos.',
                'image' => '/storage/locales/deporte/gimnasio-fit-life.avif',
                'address' => 'Avenida de la Libertad, 45',
                'city' => 'Madrid',
                'phone' => '911234555',
                'has_parking' => true,
                'opening_time' => '07:00',
                'closing_time' => '23:00',
            ],
            [
                'name' => 'Pádel Club Central',
                'type' => 'padel',
                'description' => 'Las mejores pistas de pádel de la ciudad con servicio de cafetería.',
                'image' => '/storage/locales/deporte/padel-club-central.avif',
                'address' => 'Calle Deportiva, 2',
                'city' => 'Barcelona',
                'phone' => '931234566',
                'has_parking' => true,
                'opening_time' => '08:00',
                'closing_time' => '00:00',
            ],
            [
                'name' => 'Piscina Olímpica Aqua',
                'type' => 'natacion',
                'description' => 'Disfruta de la natación en nuestras instalaciones de nivel profesional.',
                'image' => '/storage/locales/deporte/piscina-olimpica-aqua.avif',
                'address' => 'Paseo Marítimo, 100',
                'city' => 'Valencia',
                'phone' => '961234577',
                'has_parking' => false,
                'opening_time' => '06:00',
                'closing_time' => '22:00',
            ],
            [
                'name' => 'Crossfit Box Red',
                'type' => 'crossfit',
                'description' => 'Supera tus límites en nuestro box de crossfit con comunidad única.',
                'image' => '/storage/locales/deporte/crossfit-box-red.avif',
                'address' => 'Polígono Industrial, Nave 4',
                'city' => 'Sevilla',
                'phone' => '954234588',
                'has_parking' => true,
                'opening_time' => '07:30',
                'closing_time' => '21:30',
            ],
            [
                'name' => 'Centro de Yoga Zen',
                'type' => 'yoga',
                'description' => 'Encuentra tu equilibrio y paz mental con nuestras clases de yoga y meditación.',
                'image' => '/storage/locales/deporte/centro-yoga-zen.avif',
                'address' => 'Calle de la Calma, 12',
                'city' => 'Málaga',
                'phone' => '951234599',
                'has_parking' => false,
                'opening_time' => '09:00',
                'closing_time' => '21:00',
            ]
        ];

        $coords = [
            'Madrid' => ['lat' => 40.416775, 'lng' => -3.703790],
            'Barcelona' => ['lat' => 41.385064, 'lng' => 2.173403],
            'Valencia' => ['lat' => 39.469907, 'lng' => -0.376288],
            'Sevilla' => ['lat' => 37.389092, 'lng' => -5.984459],
            'Málaga' => ['lat' => 36.721261, 'lng' => -4.421266],
            'Bilbao' => ['lat' => 43.263012, 'lng' => -2.934985],
        ];

        foreach ($sportCenters as $data) {
            $data['slug'] = Str::slug($data['name']);
            $data['interest_id'] = $interest->id;
            $data['status'] = 'active'; $data['user_id'] = 1;

            if (isset($coords[$data['city']])) {
                $data['latitude'] = $coords[$data['city']]['lat'];
                $data['longitude'] = $coords[$data['city']]['lng'];
            }

            SportCenter::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }
    }
}
