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
        Permission::create(['name' => 'administrar usuarios']);
        Permission::create(['name' => 'administrar servicios']);
        Permission::create(['name' => 'administrar reservas']);
        Permission::create(['name' => 'ver dashboard admin']);
        Permission::create(['name' => 'crear reserva']);
        Permission::create(['name' => 'ver mis reservas']);
        Permission::create(['name' => 'cancelar reserva']);

        // Crear roles y asignar permisos
        $roleAdmin = Role::create(['name' => 'admin']);
        $roleAdmin->givePermissionTo([
            'administrar usuarios',
            'administrar servicios',
            'administrar reservas',
            'ver dashboard admin',
            'crear reserva',
            'ver mis reservas',
            'cancelar reserva',
        ]);

        $roleCliente = Role::create(['name' => 'cliente']);
        $roleCliente->givePermissionTo([
            'crear reserva',
            'ver mis reservas',
            'cancelar reserva',
        ]);
    }
}

