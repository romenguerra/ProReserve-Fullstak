<?php

namespace Database\Seeders;

use App\Models\Interest;
use App\Models\Barbershop;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BarbershopSeeder extends Seeder
{
    public function run(): void
    {
        // Interest 'personal' based on the InterestSeeder
        $interest = Interest::where('slug', 'personal')->first();

        if (!$interest) {
            return;
        }

        $barbershops = [
            [
                'name' => 'Barbería Clásica',
                'description' => 'Cortes y arreglos de barba tradicionales.',
                'address' => 'Calle Goya, 15',
                'city' => 'Madrid',
                'phone' => '915678901',
                'opening_time' => '09:00',
                'closing_time' => '20:00',
            ],
            [
                'name' => 'Modern Cuts',
                'description' => 'Estilos urbanos y modernos.',
                'address' => 'Paseo de la Castellana, 100',
                'city' => 'Madrid',
                'phone' => '915678902',
                'opening_time' => '10:00',
                'closing_time' => '21:00',
            ]
        ];

        foreach ($barbershops as $data) {
            $data['slug'] = Str::slug($data['name']);
            $data['interest_id'] = $interest->id;
            Barbershop::create($data);
        }
    }
}
