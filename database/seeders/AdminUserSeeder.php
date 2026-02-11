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
        // Verificar si ya existe un admin
        $adminEmail = 'admin@proreserve.com';
        
        $existingAdmin = User::where('email', $adminEmail)->first();

        if ($existingAdmin) {
            $this->command->info('❌ El usuario admin ya existe con email: ' . $adminEmail);
            $this->command->info('📧 Email: ' . $adminEmail);
            $this->command->info('🔑 Password: admin123 (si no lo has cambiado)');
            return;
        }

        // Crear usuario administrador
        $admin = User::create([
            'name' => 'Administrador',
            'email' => $adminEmail,
            'password' => Hash::make('admin123'),
            'email_verified_at' => now(), // Marcar email como verificado
        ]);

        // Asignar rol de admin
        $admin->assignRole('admin');

        $this->command->info('✅ Usuario administrador creado exitosamente');
        $this->command->info('📧 Email: ' . $adminEmail);
        $this->command->info('🔑 Password: admin123');
        $this->command->warn('⚠️  IMPORTANTE: Cambia la contraseña después del primer login');
    }
}
