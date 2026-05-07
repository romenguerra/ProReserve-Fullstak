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
                'image' => '/storage/locales/salud/hospital-privado-san-juan.avif',
                'address' => 'Avenida de la Salud, 100',
                'city' => 'Madrid',
                'phone' => '911555666',
                'has_emergency' => true,
                'has_parking' => true,
                'opening_time' => '00:00',
                'closing_time' => '23:59', // 24h
                'latitude' => 40.453054,
                'longitude' => -3.688344,
            ],
            [
                'name' => 'Clínica Médica Integral',
                'type' => 'clinica medica',
                'description' => 'Tu centro de confianza para chequeos generales, medicina preventiva y familia.',
                'image' => '/storage/locales/salud/clinica-medica-integral.avif',
                'address' => 'Calle Doctor Fleming, 45',
                'city' => 'Barcelona',
                'phone' => '931555667',
                'has_emergency' => false,
                'has_parking' => false,
                'opening_time' => '08:00',
                'closing_time' => '20:00',
                'latitude' => 41.392634,
                'longitude' => 2.142857,
            ],
            [
                'name' => 'Beauty & Health Institute',
                'type' => 'clinica estetica',
                'description' => 'Medicina estética avanzada, dermatología cosmética y tratamientos anti-aging.',
                'image' => '/storage/locales/salud/beauty-health-institute.avif',
                'address' => 'Paseo de Gracia, 20',
                'city' => 'Barcelona',
                'phone' => '931555668',
                'has_emergency' => false,
                'has_parking' => true,
                'opening_time' => '09:00',
                'closing_time' => '19:30',
            ],
            [
                'name' => 'Centro Médico Dental Sonrisas',
                'type' => 'clinica medica',
                'description' => 'Especialistas en odontología, ortodoncia e implantes dentales con la tecnología más avanzada.',
                'image' => '/storage/locales/salud/centro-medico-dental-sonrisas.avif',
                'address' => 'Avenida de la Constitución, 15',
                'city' => 'Sevilla',
                'phone' => '954555669',
                'has_emergency' => true, // Urgencias dentales
                'has_parking' => false,
                'opening_time' => '09:00',
                'closing_time' => '21:00',
            ],
            [
                'name' => 'Cuerpo y Mente Wellness Medical',
                'type' => 'clinica estetica',
                'description' => 'Tratamientos corporales, nutrición y psicología para un bienestar global.',
                'image' => '/storage/locales/salud/cuerpo-mente-wellness-medical.avif',
                'address' => 'Calle Colón, 50',
                'city' => 'Valencia',
                'phone' => '961555670',
                'has_emergency' => false,
                'has_parking' => true,
                'opening_time' => '10:00',
                'closing_time' => '20:00',
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

        foreach ($healthCenters as $data) {
            $data['slug'] = Str::slug($data['name']);
            $data['interest_id'] = $saludInterest->id;
            $data['status'] = 'active'; $data['user_id'] = 1;

            if (isset($coords[$data['city']])) {
                $data['latitude'] = $coords[$data['city']]['lat'];
                $data['longitude'] = $coords[$data['city']]['lng'];
            }

            HealthCenter::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }
    }
}
