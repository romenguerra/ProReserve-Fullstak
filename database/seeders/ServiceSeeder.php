<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use App\Models\SportCenter;
use App\Models\BeautyCenter;
use App\Models\HealthCenter;
use App\Models\LeisureCenter;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        // Servicios básicos y directos por local
        $customServices = [
            // --- GASTRONOMÍA ---
            'La Parrilla del Abuelo' => [
                ['name' => 'Reserva de Mesa', 'duration_minutes' => 90, 'price' => 0]
            ],
            'Sushi Master' => [
                ['name' => 'Reserva de Mesa', 'duration_minutes' => 90, 'price' => 0],
                ['name' => 'Menú Degustación', 'duration_minutes' => 120, 'price' => 60]
            ],
            'Healthy Vibes' => [
                ['name' => 'Reserva de Mesa', 'duration_minutes' => 60, 'price' => 0]
            ],

            // --- DEPORTES ---
            'Gimnasio FitLife' => [
                ['name' => 'Entrenamiento Libre', 'duration_minutes' => 120, 'price' => 10]
            ],
            'Club de Tenis Victoria' => [
                ['name' => 'Alquiler de Pista', 'duration_minutes' => 60, 'price' => 15]
            ],

            // --- BELLEZA ---
            'Barbería El Elegante' => [
                ['name' => 'Corte de Pelo', 'duration_minutes' => 30, 'price' => 15]
            ],
            'Glow Beauty & Spa' => [
                ['name' => 'Masaje Relajante', 'duration_minutes' => 60, 'price' => 45]
            ],

            // --- SALUD ---
            'Clínica Dental Sonrisas' => [
                ['name' => 'Limpieza Dental', 'duration_minutes' => 45, 'price' => 50]
            ],
        ];

        foreach ($customServices as $localName => $services) {
            $local = Restaurant::where('name', $localName)->first() 
                  ?? SportCenter::where('name', $localName)->first()
                  ?? BeautyCenter::where('name', $localName)->first()
                  ?? HealthCenter::where('name', $localName)->first()
                  ?? LeisureCenter::where('name', $localName)->first();

            if ($local) {
                // Limpiar servicios anteriores
                $local->services()->delete();
                
                // Crear los nuevos servicios simples
                foreach ($services as $serviceData) {
                    $local->services()->create($serviceData);
                }
            }
        }
    }
}
