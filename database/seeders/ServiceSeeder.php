<?php

namespace Database\Seeders;

use App\Models\Barbershop;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $barbershop = Barbershop::first();
        if (!$barbershop)
            return;

        $services = [
            [
                'name' => 'Corte de Pelo Clásico',
                'duration_minutes' => 30,
                'price' => 15.00,
            ],
            [
                'name' => 'Arreglo de Barba',
                'duration_minutes' => 20,
                'price' => 10.00,
            ],
            [
                'name' => 'Corte + Barba',
                'duration_minutes' => 50,
                'price' => 22.00,
            ]
        ];

        foreach ($services as $data) {
            $barbershop->services()->create($data);
        }
    }
}
