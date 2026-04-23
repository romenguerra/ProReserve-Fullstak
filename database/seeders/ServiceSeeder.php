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
        // Servicios básicos y directos por local (ASEGURANDO QUE LOS NOMBRES COINCIDAN EXACTAMENTE)
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
            'El Rincón del Mediterráneo' => [
                ['name' => 'Reserva de Mesa', 'duration_minutes' => 90, 'price' => 0]
            ],
            'Trattoria Bella Italia' => [
                ['name' => 'Reserva de Mesa', 'duration_minutes' => 90, 'price' => 0]
            ],

            // --- DEPORTES ---
            'Gimnasio Fit Life' => [
                ['name' => 'Entrenamiento Libre', 'duration_minutes' => 120, 'price' => 10],
                ['name' => 'Sesión con Entrenador Personal', 'duration_minutes' => 60, 'price' => 30]
            ],
            'Pádel Club Central' => [
                ['name' => 'Alquiler de Pista (1h)', 'duration_minutes' => 60, 'price' => 15],
                ['name' => 'Clase de Pádel', 'duration_minutes' => 60, 'price' => 25]
            ],
            'Centro de Yoga Zen' => [
                ['name' => 'Clase de Hatha Yoga', 'duration_minutes' => 75, 'price' => 12],
                ['name' => 'Sesión de Meditación', 'duration_minutes' => 45, 'price' => 8]
            ],

            // --- BELLEZA ---
            'Barbería Clásica' => [
                ['name' => 'Corte de Pelo Clásico', 'duration_minutes' => 30, 'price' => 15],
                ['name' => 'Arreglo de Barba', 'duration_minutes' => 20, 'price' => 10],
                ['name' => 'Corte + Barba', 'duration_minutes' => 45, 'price' => 22]
            ],
            'Salón de Belleza Aura' => [
                ['name' => 'Corte y Peinado', 'duration_minutes' => 60, 'price' => 35],
                ['name' => 'Manicura Completa', 'duration_minutes' => 45, 'price' => 20]
            ],
            'Oasis Wellness & Masajes' => [
                ['name' => 'Masaje Relajante', 'duration_minutes' => 60, 'price' => 45],
                ['name' => 'Circuito Spa', 'duration_minutes' => 90, 'price' => 30]
            ],

            // --- SALUD ---
            'Centro Médico Dental Sonrisas' => [
                ['name' => 'Limpieza Dental', 'duration_minutes' => 45, 'price' => 50],
                ['name' => 'Revisión General', 'duration_minutes' => 30, 'price' => 0]
            ],
            'Clínica Médica Integral' => [
                ['name' => 'Consulta Medicina General', 'duration_minutes' => 20, 'price' => 40],
                ['name' => 'Analítica Completa', 'duration_minutes' => 15, 'price' => 30]
            ],

            // --- OCIO ---
            'Cines Splendid' => [
                ['name' => 'Entrada de Cine', 'duration_minutes' => 120, 'price' => 9]
            ],
            'Bolera Pin Strike' => [
                ['name' => 'Partida de Bolos', 'duration_minutes' => 45, 'price' => 7]
            ],
        ];

        foreach ($customServices as $localName => $services) {
            $local = Restaurant::where('name', $localName)->first() 
                  ?? SportCenter::where('name', $localName)->first()
                  ?? BeautyCenter::where('name', $localName)->first()
                  ?? HealthCenter::where('name', $localName)->first()
                  ?? LeisureCenter::where('name', $localName)->first();

            if ($local) {
                // Limpiar servicios anteriores para evitar duplicados si se corre varias veces
                $local->services()->delete();
                
                // Crear los nuevos servicios
                foreach ($services as $serviceData) {
                    $local->services()->create($serviceData);
                }
            }
        }
    }
}
