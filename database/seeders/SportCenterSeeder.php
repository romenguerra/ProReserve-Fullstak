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
                'image' => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&q=80&w=800',
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
                'image' => 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?auto=format&fit=crop&q=80&w=800',
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
                'image' => 'https://images.unsplash.com/photo-1519315901367-f34ff9154487?auto=format&fit=crop&q=80&w=800',
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
                'image' => 'https://images.unsplash.com/photo-1541534741688-6078c6bfb5c5?auto=format&fit=crop&q=80&w=800',
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
                'image' => 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?auto=format&fit=crop&q=80&w=800',
                'address' => 'Calle de la Calma, 12',
                'city' => 'Málaga',
                'phone' => '951234599',
                'has_parking' => false,
                'opening_time' => '09:00',
                'closing_time' => '21:00',
            ]
        ];

        foreach ($sportCenters as $data) {
            $data['slug'] = Str::slug($data['name']);
            $data['interest_id'] = $interest->id;
            SportCenter::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }
    }
}
