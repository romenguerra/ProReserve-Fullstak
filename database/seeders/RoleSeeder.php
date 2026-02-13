<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Resetear caché de roles y permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear permisos
        $permissions = [
            'administrar usuarios',
            'administrar servicios',
            'administrar reservas',
            'ver dashboard admin',
            'crear reserva',
            'ver mis reservas',
            'cancelar reserva',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Crear roles y asignar permisos
        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
        $roleAdmin->syncPermissions($permissions);

        $roleCliente = Role::firstOrCreate(['name' => 'cliente']);
        $roleCliente->syncPermissions([
            'crear reserva',
            'ver mis reservas',
            'cancelar reserva',
        ]);
    }
}
