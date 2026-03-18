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
                'description' => 'Las mejores carnes a la brasa con el sabor tradicional de leña.',
                'image' => 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&q=80&w=800',
                'address' => 'Calle Mayor, 10',
                'city' => 'Madrid',
                'phone' => '911234567',
                'has_terrace' => true,
                'opening_time' => '13:00',
                'closing_time' => '23:30',
            ],
            [
                'name' => 'Sushi Master',
                'description' => 'Auténtico sushi japonés preparado al momento por maestros artesanos.',
                'image' => 'https://images.unsplash.com/photo-1579871494447-9811cf80d66c?auto=format&fit=crop&q=80&w=800',
                'address' => 'Gran Vía, 40',
                'city' => 'Madrid',
                'phone' => '911234568',
                'has_terrace' => false,
                'opening_time' => '12:00',
                'closing_time' => '22:30',
            ],
            [
                'name' => 'El Rincón del Mediterráneo',
                'description' => 'Sabores frescos del mar y la huerta en un ambiente acogedor frente al puerto.',
                'image' => 'https://images.unsplash.com/photo-1559339352-11d035aa65de?auto=format&fit=crop&q=80&w=800',
                'address' => 'Passeig de Colom, 15',
                'city' => 'Barcelona',
                'phone' => '931234569',
                'has_terrace' => true,
                'opening_time' => '13:00',
                'closing_time' => '00:00',
            ],
            [
                'name' => 'Trattoria Bella Italia',
                'description' => 'Pasta fresca artesanal y pizzas al horno de piedra siguiendo recetas milenarias.',
                'image' => 'https://images.unsplash.com/photo-1551183053-bf91a1d81141?auto=format&fit=crop&q=80&w=800',
                'address' => 'Plaça de la Reina, 5',
                'city' => 'Valencia',
                'phone' => '961234570',
                'has_terrace' => true,
                'opening_time' => '13:30',
                'closing_time' => '23:30',
            ],
            [
                'name' => 'Sakura Garden',
                'description' => 'Un oasis de paz y gastronomía nipona en el corazón de la ciudad.',
                'image' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&q=80&w=800',
                'address' => 'Calle Sierpes, 24',
                'city' => 'Sevilla',
                'phone' => '954234571',
                'has_terrace' => false,
                'opening_time' => '20:00',
                'closing_time' => '00:30',
            ],
            [
                'name' => 'Steaks & Go',
                'description' => 'Especialistas en cortes premium madurados y cocina de autor.',
                'image' => 'https://images.unsplash.com/photo-1600891964599-f61ba0e24092?auto=format&fit=crop&q=80&w=800',
                'address' => 'Alameda Mazarredo, 12',
                'city' => 'Bilbao',
                'phone' => '944234572',
                'has_terrace' => false,
                'opening_time' => '13:00',
                'closing_time' => '23:00',
            ],
            [
                'name' => 'Healthy Vibes',
                'description' => 'Cocina consciente, saludable y deliciosa con productos de proximidad.',
                'image' => 'https://images.unsplash.com/photo-1498837167922-ddd27525d352?auto=format&fit=crop&q=80&w=800',
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
