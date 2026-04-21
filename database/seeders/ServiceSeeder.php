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
        // Mapa detallado de locales y sus servicios específicos
        $customServices = [
            // --- GASTRONOMÍA ---
            'La Parrilla del Abuelo' => [
                ['name' => 'Mesa cerca de la Parrilla', 'description' => 'Siente el calor de las brasas en una ubicación privilegiada.', 'duration_minutes' => 90, 'price' => 0.00],
                ['name' => 'Menú Degustación Carnes', 'description' => 'Selección de 5 cortes premium madurados.', 'duration_minutes' => 120, 'price' => 55.00],
            ],
            'Sushi Master' => [
                ['name' => 'Experiencia Omakase', 'description' => 'Cena exclusiva en barra servida directamente por el itamae.', 'duration_minutes' => 150, 'price' => 85.00],
                ['name' => 'Reserva de Mesa Estándar', 'description' => 'Mesa tradicional en el salón principal.', 'duration_minutes' => 90, 'price' => 0.00],
            ],
            'Healthy Vibes' => [
                ['name' => 'Reserva Brunch Saludable', 'description' => 'Pack completo de desayuno-comida energético.', 'duration_minutes' => 60, 'price' => 18.00],
                ['name' => 'Reserva Mesa Terraza', 'description' => 'Disfruta de tu comida al aire libre en nuestro jardín.', 'duration_minutes' => 90, 'price' => 2.00],
            ],

            // --- DEPORTES ---
            'Gimnasio FitLife' => [
                ['name' => 'Entrenamiento Personal (1h)', 'description' => 'Sesión individual con planificación personalizada.', 'duration_minutes' => 60, 'price' => 35.00],
                ['name' => 'Evaluación Biofísica', 'description' => 'Análisis de composición corporal y objetivos.', 'duration_minutes' => 45, 'price' => 25.00],
            ],
            'Club de Tenis Victoria' => [
                ['name' => 'Alquiler Pista de Tierra', 'description' => '60 minutos de juego en pista de tierra batida.', 'duration_minutes' => 60, 'price' => 15.00],
                ['name' => 'Clase de Tenis Express', 'description' => 'Corrección técnica rápida para jugadores intermedios.', 'duration_minutes' => 45, 'price' => 25.00],
            ],

            // --- BELLEZA ---
            'Barbería El Elegante' => [
                ['name' => 'Corte & Afeitado Clásico', 'description' => 'Tratamiento completo con toalla caliente y navaja.', 'duration_minutes' => 50, 'price' => 28.00],
                ['name' => 'Arreglo de Barba Royale', 'description' => 'Perfilado y aceites hidratantes.', 'duration_minutes' => 25, 'price' => 12.00],
            ],
            'Glow Beauty & Spa' => [
                ['name' => 'Limpieza Facial Profunda', 'description' => 'Tratamiento con ultrasonidos y mascarilla artesana.', 'duration_minutes' => 60, 'price' => 45.00],
                ['name' => 'Masaje Relajante con Piedras', 'description' => 'Terapia de calor para eliminar el estrés.', 'duration_minutes' => 80, 'price' => 65.00],
            ],

            // --- SALUD ---
            'Clínica Dental Sonrisas' => [
                ['name' => 'Limpieza Dental Pro', 'description' => 'Eliminación de sarro mediante ultrasonidos.', 'duration_minutes' => 40, 'price' => 50.00],
                ['name' => 'Consulta de Ortodoncia', 'description' => 'Valoración gratuita para tratamientos correctivos.', 'duration_minutes' => 30, 'price' => 0.00],
            ],
        ];

        // Procesar la asignación
        foreach ($customServices as $localName => $services) {
            $local = Restaurant::where('name', $localName)->first() 
                  ?? SportCenter::where('name', $localName)->first()
                  ?? BeautyCenter::where('name', $localName)->first()
                  ?? HealthCenter::where('name', $localName)->first()
                  ?? LeisureCenter::where('name', $localName)->first();

            if ($local) {
                // Limpiamos servicios anteriores para este local específico
                $local->services()->delete();
                
                // Creamos los nuevos servicios
                foreach ($services as $serviceData) {
                    $local->services()->create($serviceData);
                }
            }
        }
    }
}
