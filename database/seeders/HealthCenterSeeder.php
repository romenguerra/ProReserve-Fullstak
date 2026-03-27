<?php

namespace Database\Seeders;

use App\Models\Interest;
use App\Models\HealthCenter;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class HealthCenterSeeder extends Seeder
{
    public function run(): void
    {
        $saludInterest = Interest::where('slug', 'salud')->first();

        if (!$saludInterest) {
            return;
        }

        $healthCenters = [
            [
                'name' => 'Hospital Privado San Juan',
                'type' => 'hospital',
                'description' => 'Centro médico de alta tecnología con atención de urgencias 24h y especialistas de primer nivel.',
                'image' => 'https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?auto=format&fit=crop&q=80&w=800',
                'address' => 'Avenida de la Salud, 100',
                'city' => 'Madrid',
                'phone' => '911555666',
                'has_emergency' => true,
                'wheelchair_access' => true,
                'has_parking' => true,
                'opening_time' => '00:00',
                'closing_time' => '23:59', // 24h
            ],
            [
                'name' => 'Clínica Médica Integral',
                'type' => 'clinica medica',
                'description' => 'Tu centro de confianza para chequeos generales, medicina preventiva y familia.',
                'image' => 'https://images.unsplash.com/photo-1538108149393-fbbd81895907?auto=format&fit=crop&q=80&w=800',
                'address' => 'Calle Doctor Fleming, 45',
                'city' => 'Barcelona',
                'phone' => '931555667',
                'has_emergency' => false,
                'wheelchair_access' => true,
                'has_parking' => false,
                'opening_time' => '08:00',
                'closing_time' => '20:00',
            ],
            [
                'name' => 'Beauty & Health Institute',
                'type' => 'clinica estetica',
                'description' => 'Medicina estética avanzada, dermatología cosmética y tratamientos anti-aging.',
                'image' => 'https://images.unsplash.com/photo-1579684385127-1ef15d508118?auto=format&fit=crop&q=80&w=800',
                'address' => 'Paseo de Gracia, 20',
                'city' => 'Barcelona',
                'phone' => '931555668',
                'has_emergency' => false,
                'wheelchair_access' => true,
                'has_parking' => true,
                'opening_time' => '09:00',
                'closing_time' => '19:30',
            ],
            [
                'name' => 'Centro Médico Dental Sonrisas',
                'type' => 'clinica medica',
                'description' => 'Especialistas en odontología, ortodoncia e implantes dentales con la tecnología más avanzada.',
                'image' => 'https://images.unsplash.com/photo-1606811841689-23dfddce3e95?auto=format&fit=crop&q=80&w=800',
                'address' => 'Avenida de la Constitución, 15',
                'city' => 'Sevilla',
                'phone' => '954555669',
                'has_emergency' => true, // Urgencias dentales
                'wheelchair_access' => true,
                'has_parking' => false,
                'opening_time' => '09:00',
                'closing_time' => '21:00',
            ],
            [
                'name' => 'Cuerpo y Mente Wellness Medical',
                'type' => 'clinica estetica',
                'description' => 'Tratamientos corporales, nutrición y psicología para un bienestar global.',
                'image' => 'https://images.unsplash.com/photo-1600508774634-4e11d34730e2?auto=format&fit=crop&q=80&w=800',
                'address' => 'Calle Colón, 50',
                'city' => 'Valencia',
                'phone' => '961555670',
                'has_emergency' => false,
                'wheelchair_access' => true,
                'has_parking' => true,
                'opening_time' => '10:00',
                'closing_time' => '20:00',
            ]
        ];

        foreach ($healthCenters as $data) {
            $data['slug'] = Str::slug($data['name']);
            $data['interest_id'] = $saludInterest->id;
            HealthCenter::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }
    }
}
