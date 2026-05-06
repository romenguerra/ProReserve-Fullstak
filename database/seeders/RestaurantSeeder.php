<?php

namespace Database\Seeders;

use App\Models\Interest;
use App\Models\Restaurant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RestaurantSeeder extends Seeder
{
    public function run(): void
    {
        $interest = Interest::where('slug', 'gastronomia')->first();

        if (!$interest) {
            return;
        }

        $restaurants = [
            [
                'name' => 'La Parrilla del Abuelo',
                'type' => 'asador',
                'description' => 'Las mejores carnes a la brasa con el sabor tradicional de leña.',
                'image' => '/storage/locales/gastronomia/la-parrilla-del-abuelo.avif',
                'address' => 'Calle Mayor, 10',
                'city' => 'Madrid',
                'phone' => '911234567',
                'has_terrace' => true,
                'opening_time' => '13:00',
                'closing_time' => '23:30',
            ],
            [
                'name' => 'Sushi Master',
                'type' => 'japones',
                'description' => 'Auténtico sushi japonés preparado al momento por maestros artesanos.',
                'image' => '/storage/locales/gastronomia/shushi-master.avif',
                'address' => 'Gran Vía, 40',
                'city' => 'Madrid',
                'phone' => '911234568',
                'has_terrace' => false,
                'opening_time' => '12:00',
                'closing_time' => '22:30',
            ],
            [
                'name' => 'El Rincón del Mediterráneo',
                'type' => 'mediterraneo',
                'description' => 'Sabores frescos del mar y la huerta en un ambiente acogedor frente al puerto.',
                'image' => '/storage/locales/gastronomia/el-rincon-del-mediterraneo.avif',
                'address' => 'Passeig de Colom, 15',
                'city' => 'Barcelona',
                'phone' => '931234569',
                'has_terrace' => true,
                'opening_time' => '13:00',
                'closing_time' => '00:00',
            ],
            [
                'name' => 'Trattoria Bella Italia',
                'type' => 'italiano',
                'description' => 'Pasta fresca artesanal y pizzas al horno de piedra siguiendo recetas milenarias.',
                'image' => '/storage/locales/gastronomia/tratoria-bella-italia.avif',
                'address' => 'Plaça de la Reina, 5',
                'city' => 'Valencia',
                'phone' => '961234570',
                'has_terrace' => true,
                'opening_time' => '13:30',
                'closing_time' => '23:30',
            ],
            [
                'name' => 'Sakura Garden',
                'type' => 'japones',
                'description' => 'Un oasis de paz y gastronomía nipona en el corazón de la ciudad.',
                'image' => '/storage/locales/gastronomia/sakura-garden.avif',
                'address' => 'Calle Sierpes, 24',
                'city' => 'Sevilla',
                'phone' => '954234571',
                'has_terrace' => false,
                'opening_time' => '20:00',
                'closing_time' => '00:30',
            ],
            [
                'name' => 'Steaks & Go',
                'type' => 'steakhouse',
                'description' => 'Especialistas en cortes premium madurados y cocina de autor.',
                'image' => '/storage/locales/gastronomia/steaks-&-go.avif',
                'address' => 'Alameda Mazarredo, 12',
                'city' => 'Bilbao',
                'phone' => '944234572',
                'has_terrace' => false,
                'opening_time' => '13:00',
                'closing_time' => '23:00',
            ],
            [
                'name' => 'Healthy Vibes',
                'type' => 'saludable',
                'description' => 'Cocina consciente, saludable y deliciosa con productos de proximidad.',
                'image' => '/storage/locales/gastronomia/healty-vibes.avif',
                'address' => 'Calle Larios, 8',
                'city' => 'Málaga',
                'phone' => '951234573',
                'has_terrace' => true,
                'opening_time' => '09:00',
                'closing_time' => '21:00',
            ]
        ];

        foreach ($restaurants as $data) {
            $data['slug'] = Str::slug($data['name']);
            $data['interest_id'] = $interest->id;
            Restaurant::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }
    }
}
