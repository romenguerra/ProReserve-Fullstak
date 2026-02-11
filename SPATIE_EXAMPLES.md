# 🔐 Guía de Uso: Spatie Laravel Permission

## ✅ Configuración Completada

- ✓ Paquete instalado
- ✓ Migraciones ejecutadas
- ✓ Modelo User configurado
- ✓ Roles y permisos creados
- ✓ Middleware registrado

---

## 📋 Roles y Permisos Creados

### Roles:
- **admin**: Acceso completo al sistema
- **cliente**: Acceso limitado a funcionalidades de usuario

### Permisos:
- `administrar usuarios`
- `administrar servicios`
- `administrar reservas`
- `ver dashboard admin`
- `crear reserva`
- `ver mis reservas`
- `cancelar reserva`

---

## 🚀 Ejemplos de Uso

### 1️⃣ Asignar Rol a un Usuario

```php
use App\Models\User;

// Obtener usuario
$user = User::find(1);

// Asignar rol
$user->assignRole('admin');

// O asignar múltiples roles
$user->assignRole(['admin', 'cliente']);

// Remover rol
$user->removeRole('cliente');

// Sincronizar roles (reemplaza todos los roles actuales)
$user->syncRoles(['admin']);
```

### 2️⃣ Verificar Roles en Controladores

```php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        // Verificar si el usuario tiene un rol
        if ($request->user()->hasRole('admin')) {
            return view('admin.dashboard');
        }

        // Verificar múltiples roles (tiene alguno de ellos)
        if ($request->user()->hasAnyRole(['admin', 'moderador'])) {
            // código
        }

        // Verificar todos los roles
        if ($request->user()->hasAllRoles(['admin', 'superadmin'])) {
            // código
        }

        return abort(403);
    }
}
```

### 3️⃣ Proteger Rutas con Middleware

```php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReservaController;

// Ruta solo para administradores
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/usuarios', [AdminController::class, 'usuarios'])->name('admin.usuarios');
    Route::get('/admin/configuracion', [AdminController::class, 'configuracion']);
});

// Ruta para admin O cliente
Route::middleware(['auth', 'role:admin|cliente'])->group(function () {
    Route::get('/reservas', [ReservaController::class, 'index'])->name('reservas.index');
});

// Proteger por permiso específico
Route::middleware(['auth', 'permission:administrar servicios'])->group(function () {
    Route::resource('servicios', ServicioController::class);
});

// Múltiples permisos (requiere todos)
Route::middleware(['auth', 'permission:crear reserva,ver mis reservas'])->group(function () {
    Route::post('/reservas', [ReservaController::class, 'store']);
});
```

### 4️⃣ Verificar Permisos en Controladores

```php
use Illuminate\Http\Request;

public function destroy(Request $request, $id)
{
    // Verificar si tiene el permiso
    if ($request->user()->can('administrar reservas')) {
        // Eliminar reserva
    }

    // O usar el helper de autorización
    $this->authorize('administrar reservas');

    // código de eliminación
}
```

### 5️⃣ Usar en Vistas Blade (si las usas)

```blade
@role('admin')
    <p>Solo visible para administradores</p>
@endrole

@hasrole('admin')
    <p>También solo para administradores</p>
@endhasrole

@hasanyrole('admin|moderador')
    <p>Visible para admin o moderador</p>
@endhasanyrole

@can('administrar usuarios')
    <a href="/admin/usuarios">Administrar Usuarios</a>
@endcan
```

### 6️⃣ Compartir Roles con Vue.js (Inertia)

Edita `app/Http/Middleware/HandleInertiaRequests.php`:

```php
public function share(Request $request): array
{
    return [
        ...parent::share($request),
        'auth' => [
            'user' => $request->user() ? [
                'id' => $request->user()->id,
                'name' => $request->user()->name,
                'email' => $request->user()->email,
                'roles' => $request->user()->getRoleNames(),
                'permissions' => $request->user()->getAllPermissions()->pluck('name'),
            ] : null,
        ],
    ];
}
```

### 7️⃣ Usar Roles en Componentes Vue

```vue
<template>
  <div>
    <!-- Solo mostrar para admin -->
    <div v-if="hasRole('admin')">
      <h1>Panel de Administración</h1>
      <Link href="/admin/usuarios">Administrar Usuarios</Link>
    </div>

    <!-- Mostrar según permiso -->
    <button v-if="can('crear reserva')" @click="crearReserva()">
      Nueva Reserva
    </button>
  </div>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const auth = computed(() => page.props.auth);

const hasRole = (role) => {
  return auth.value.user?.roles?.includes(role);
};

const can = (permission) => {
  return auth.value.user?.permissions?.some(p => p === permission);
};
</script>
```

### 8️⃣ Crear Usuario Admin desde Tinker

```bash
php artisan tinker
```

```php
$user = User::create([
    'name' => 'Administrador',
    'email' => 'admin@proreserve.com',
    'password' => bcrypt('password123')
]);

$user->assignRole('admin');
```

### 9️⃣ Verificar Roles/Permisos en Consultas

```php
// Obtener usuarios con rol admin
$admins = User::role('admin')->get();

// Obtener usuarios con permiso específico
$users = User::permission('administrar servicios')->get();

// Obtener usuarios sin un rol
$sinRol = User::doesntHaveRole('admin')->get();
```

### 🔟 Dar Permiso Directo a Usuario (sin rol)

```php
$user = User::find(1);

// Dar permiso directo
$user->givePermissionTo('administrar usuarios');

// Verificar
if ($user->hasPermissionTo('administrar usuarios')) {
    // código
}
```

---

## 🎯 Caso de Uso: Sistema de Reservas

### Escenario Típico:

1. **Usuario se registra** → Asignar rol "cliente" automáticamente
2. **Cliente** → Puede crear, ver y cancelar sus reservas
3. **Admin** → Puede gestionar todo el sistema

### Implementación en RegisteredUserController:

```php
use App\Models\User;

public function store(Request $request)
{
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    // Asignar rol cliente por defecto
    $user->assignRole('cliente');

    Auth::login($user);

    return redirect(route('dashboard'));
}
```

---

## 📚 Recursos Adicionales

- [Documentación Oficial Spatie](https://spatie.be/docs/laravel-permission)
- [GitHub Repo](https://github.com/spatie/laravel-permission)

---

## ⚡ Comandos Útiles

```bash
# Limpiar caché de permisos
php artisan permission:cache-reset

# Crear nuevo rol/permiso
php artisan tinker
>>> Role::create(['name' => 'moderador']);
>>> Permission::create(['name' => 'editar posts']);

# Ejecutar seeder de roles nuevamente
php artisan db:seed --class=RoleSeeder
```
