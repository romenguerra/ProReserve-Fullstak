# 🚀 Prueba Rápida del Sistema de Roles

## 1️⃣ Crear Usuario Administrador

Abre una terminal y ejecuta:

```bash
php artisan tinker
```

Luego ejecuta estos comandos:

```php
$admin = \App\Models\User::create([
    'name' => 'Administrador',
    'email' => 'admin@proreserve.com',
    'password' => bcrypt('admin123')
]);

$admin->assignRole('admin');

// Verificar
$admin->getRoleNames(); // Debería mostrar: ["admin"]
```

Sal de tinker con `exit` o `Ctrl+D`

## 2️⃣ Crear Usuario Cliente de Prueba

```bash
php artisan tinker
```

```php
$cliente = \App\Models\User::create([
    'name' => 'Cliente Test',
    'email' => 'cliente@test.com',
    'password' => bcrypt('cliente123')
]);

$cliente->assignRole('cliente');

// Verificar
$cliente->getRoleNames(); // Debería mostrar: ["cliente"]
```

## 3️⃣ Prueba de Registro Automático

Registra un nuevo usuario desde la interfaz web:
- Ve a `/register`
- Completa el formulario
- El usuario se creará automáticamente con rol **cliente**

## 4️⃣ Verificar en Base de Datos

```bash
php artisan tinker
```

```php
// Ver todos los roles
\Spatie\Permission\Models\Role::all();

// Ver todos los permisos
\Spatie\Permission\Models\Permission::all();

// Ver usuarios con sus roles
\App\Models\User::with('roles')->get();

// Ver usuarios admin
\App\Models\User::role('admin')->get();
```

## 5️⃣ Probar en la Web

### Login como Admin:
- Email: `admin@proreserve.com`
- Password: `admin123`

### Login como Cliente:
- Email: `cliente@test.com`
- Password: `cliente123`

## 6️⃣ Usar en Controladores

Crea un controlador de prueba:

```bash
php artisan make:controller AdminController
```

Edita `app/Http/Controllers/AdminController.php`:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        // Solo llegará aquí si tiene el middleware 'role:admin'
        return Inertia::render('Admin/Dashboard');
    }

    public function usuarios(Request $request)
    {
        // Obtener todos los usuarios con sus roles
        $usuarios = \App\Models\User::with('roles')->get();
        
        return Inertia::render('Admin/Usuarios', [
            'usuarios' => $usuarios
        ]);
    }
}
```

## 7️⃣ Agregar Rutas Protegidas

En `routes/web.php`:

```php
use App\Http\Controllers\AdminController;

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/usuarios', [AdminController::class, 'usuarios'])->name('admin.usuarios');
});
```

## 8️⃣ Verificar en Vue.js

Crea un componente de prueba o edita `Dashboard.vue`:

```vue
<script setup>
import { usePermissions } from '@/Composables/usePermissions';

const { user, roles, permissions, isAdmin, can } = usePermissions();

console.log('Usuario:', user.value);
console.log('Roles:', roles.value);
console.log('Permisos:', permissions.value);
console.log('Es Admin?', isAdmin());
console.log('Puede crear reserva?', can('crear reserva'));
</script>

<template>
    <div>
        <p v-if="isAdmin()">Eres administrador ✅</p>
        <p v-else>Eres cliente 👤</p>
    </div>
</template>
```

## 9️⃣ Probar Restricciones

### Intenta acceder a una ruta de admin como cliente:
1. Login como cliente (`cliente@test.com`)
2. Intenta ir a `/admin/dashboard`
3. Deberías ver error 403 (Forbidden)

### Como admin:
1. Login como admin (`admin@proreserve.com`)
2. Accede a `/admin/dashboard`
3. Deberías tener acceso ✅

## 🔟 Comandos Útiles

```bash
# Ver lista de usuarios y roles
php artisan tinker
>>> \App\Models\User::with('roles')->get()->map(fn($u) => ['name' => $u->name, 'email' => $u->email, 'roles' => $u->getRoleNames()]);

# Limpiar caché de permisos
php artisan permission:cache-reset

# Re-ejecutar seeders
php artisan db:seed --class=RoleSeeder

# Ver estructura de base de datos
php artisan db:show
```

## ✅ Checklist de Verificación

- [ ] Usuarios se crean correctamente
- [ ] Rol "cliente" se asigna automáticamente en el registro
- [ ] Puede crear usuario admin manualmente
- [ ] Roles aparecen en `$page.props.auth.user.roles` en Vue
- [ ] Permisos aparecen en `$page.props.auth.user.permissions` en Vue
- [ ] Middleware `role:admin` bloquea acceso a no-admin
- [ ] Composable `usePermissions()` funciona en componentes Vue
- [ ] Se pueden crear reservas con permiso `crear reserva`

## 📚 Siguientes Pasos

1. Crear modelos: Reserva, Servicio, Horario
2. Crear controladores para cada modelo
3. Crear vistas Vue para admin y cliente
4. Implementar lógica de negocio de reservas
5. Agregar más permisos según necesidades

---

**¡Sistema de roles configurado y listo para usar!** 🎉
