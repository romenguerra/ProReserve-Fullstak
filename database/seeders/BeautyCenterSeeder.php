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
                'image' => '/storage/locales/imagen personal/barberia-clasica.avif',
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
                'image' => '/storage/locales/imagen personal/moderm-cuts.avif',
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
                'image' => '/storage/locales/imagen personal/salon-belleza-aura.avif',
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
                'image' => '/storage/locales/imagen personal/centro-estetica-zen.avif',
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
                'image' => '/storage/locales/imagen personal/oasis-wellness.avif',
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
