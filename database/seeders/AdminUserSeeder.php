<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. USUARIO ADMINISTRADOR
        $admin = User::updateOrCreate(
            ['email' => 'admin@proreserve.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('admin');

        // 2. USUARIO CLIENTE (Básico)
        $cliente = User::updateOrCreate(
            ['email' => 'romen@proreserve.com'],
            [
                'name' => 'Romen Cliente',
                'password' => Hash::make('230904'),
                'email_verified_at' => now(),
            ]
        );
        $cliente->assignRole('cliente');

        // 3. USUARIO LOCAL (Empresario)
        $local = User::updateOrCreate(
            ['email' => 'laura@proreserve.com'],
            [
                'name' => 'Laura Empresaria',
                'password' => Hash::make('230904'),
                'email_verified_at' => now(),
            ]
        );
        $local->assignRole('empresa');

        $this->command->info('✅ Usuarios fijos creados/actualizados exitosamente:');
        $this->command->info('👑 Admin: admin@proreserve.com / admin123');
        $this->command->info('👤 Cliente: romen@proreserve.com / 230904');
        $this->command->info('🏢 Local: laura@proreserve.com / 230904');
    }
}
