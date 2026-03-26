<?php

namespace Database\Seeders;

use App\Models\Interest;
use App\Models\BeautyCenter;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BeautyCenterSeeder extends Seeder
{
    public function run(): void
    {
        // Reuse the Personal interest or create a specific Beauty interest if needed.
        // I will use "personal" as the category for "beauty/image" in the original db design
        $personalInterest = Interest::where('slug', 'personal')->first();

        if (!$personalInterest) {
            return;
        }

        $beautyCenters = [
            [
                'name' => 'Barbería Clásica',
                'type' => 'barberia',
                'description' => 'Cortes y arreglos de barba tradicionales. Especialistas en afeitado a navaja.',
                'image' => 'https://images.unsplash.com/photo-1599351431202-1e0f0137899a?auto=format&fit=crop&q=80&w=800',
                'address' => 'Calle Goya, 15',
                'city' => 'Madrid',
                'phone' => '915678901',
                'opening_time' => '09:00',
                'closing_time' => '20:00',
            ],
            [
                'name' => 'Modern Cuts',
                'type' => 'barberia',
                'description' => 'Estilos urbanos y modernos. Tratamientos capilares avanzados para hombres.',
                'image' => 'https://images.unsplash.com/photo-1585747860715-2ba37e788b70?auto=format&fit=crop&q=80&w=800',
                'address' => 'Paseo de la Castellana, 100',
                'city' => 'Madrid',
                'phone' => '915678902',
                'opening_time' => '10:00',
                'closing_time' => '21:00',
            ],
            [
                'name' => 'Salón de Belleza Aura',
                'type' => 'peluqueria',
                'description' => 'Peluquería unisex de alto standing. Colorimetría, mechas balayage y tratamientos orgánicos.',
                'image' => 'https://images.unsplash.com/photo-1560066984-138dadb4c035?auto=format&fit=crop&q=80&w=800',
                'address' => 'Rambla de Catalunya, 45',
                'city' => 'Barcelona',
                'phone' => '935678903',
                'opening_time' => '09:30',
                'closing_time' => '20:00',
            ],
            [
                'name' => 'Centro de Estética Zen',
                'type' => 'estetica',
                'description' => 'Tratamientos faciales rejuvenecedores, maderoterapia y aparatología corporal avanzada.',
                'image' => 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?auto=format&fit=crop&q=80&w=800',
                'address' => 'Calle Tetuán, 12',
                'city' => 'Sevilla',
                'phone' => '955678904',
                'opening_time' => '10:00',
                'closing_time' => '19:00',
            ],
            [
                'name' => 'Oasis Wellness & Masajes',
                'type' => 'masajes',
                'description' => 'Masajes relajantes, descontracturantes, piedras calientes y rituales spa del mundo.',
                'image' => 'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?auto=format&fit=crop&q=80&w=800',
                'address' => 'Avenida de Francia, 80',
                'city' => 'Valencia',
                'phone' => '965678905',
                'opening_time' => '11:00',
                'closing_time' => '21:00',
            ]
        ];

        foreach ($beautyCenters as $data) {
            $data['slug'] = Str::slug($data['name']);
            $data['interest_id'] = $personalInterest->id;
            BeautyCenter::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }
    }
}
