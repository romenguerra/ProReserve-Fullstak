<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create {--name=} {--email=} {--password=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crear un nuevo usuario administrador';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔐 Crear Nuevo Administrador');
        $this->newLine();

        // Obtener datos del admin (desde opciones o preguntar)
        $name = $this->option('name') ?: $this->ask('Nombre del administrador');
        $email = $this->option('email') ?: $this->ask('Email del administrador');
        $password = $this->option('password') ?: $this->secret('Contraseña (mínimo 8 caracteres)');

        // Validar datos
        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ], [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            $this->newLine();
            $this->error('❌ Error de validación:');
            foreach ($validator->errors()->all() as $error) {
                $this->error('  • ' . $error);
            }
            return 1;
        }

        // Crear usuario
        try {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'email_verified_at' => now(),
            ]);

            // Asignar rol de admin
            $user->assignRole('admin');

            $this->newLine();
            $this->info('✅ Administrador creado exitosamente');
            $this->newLine();
            $this->table(
                ['Campo', 'Valor'],
                [
                    ['Nombre', $user->name],
                    ['Email', $user->email],
                    ['Rol', 'admin'],
                    ['ID', $user->id],
                ]
            );
            $this->newLine();
            $this->comment('Ya puedes iniciar sesión con estas credenciales.');

            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Error al crear el administrador: ' . $e->getMessage());
            return 1;
        }
    }
}
